<?php

namespace App\Http\Webhooks;

use App\Enums\Bot\TelegramBotActions;
use App\Models\Stock;
use App\Services\Order\OrderItemInventoryService;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;
use DefStudio\Telegraph\Models\TelegraphBot;
use DefStudio\Telegraph\Models\TelegraphChat;
use Illuminate\Support\Stringable;

class TelegramBotHandler extends WebhookHandler
{
    /**
     * TelegramBot model.
     *
     * @var \App\Models\Bots\Telegram\TelegramBot
     */
    protected TelegraphBot $bot;

    /**
     * TelegramChat model.
     *
     * @var \App\Models\Bots\Telegram\TelegramChat
     */
    protected TelegraphChat $chat;

    /**
     * The service responsible for managing order item inventory.
     */
    private OrderItemInventoryService $inventoryService;

    /**
     * TelegramBotHandler constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->inventoryService = app(OrderItemInventoryService::class);
    }

    /**
     * Handle incoming chat message.
     */
    protected function handleChatMessage(Stringable $text): void
    {
        // .. do nothing
    }

    /**
     * Handle reserve confirmation action.
     */
    public function reserveConfirm(): void
    {
        $this->inventoryService->reserveItem($this->data->get('id'));

        $this->actionReply(TelegramBotActions::RESERVE_CONFIRM->name());
    }

    /**
     * Handle collect confirmation action.
     */
    public function collectConfirm(): void
    {
        $this->inventoryService->collectItem($this->data->get('id'));

        $this->actionReply(TelegramBotActions::COLLECT_CONFIRM->name());
    }

    /**
     * Handle reserve dismissal action
     */
    public function outOfStock(): void
    {
        $this->inventoryService->outOfStock($this->data->get('id'));

        $this->actionReply(TelegramBotActions::OUT_OF_STOCK->name());
    }

    /**
     * Handle an action reply from the user.
     *
     * This method is responsible for processing an action (typically a button press)
     * performed by the user in response to a previous message. It removes any existing
     * inline keyboards from the message, sends a webhook with the text of the pressed
     * button, and updates the original message by appending the text of the pressed button
     * as a confirmation of which action was taken.
     */
    protected function actionReply(string $text): void
    {
        // $this->deleteKeyboard(); // editCaption enough
        $message = $this->callbackQuery->message()->text() . "\n\n$text";
        $this->chat->editCaption($this->messageId)->message($message)->send();
        $this->reply($text);
    }

    /**
     * Command for saving the chat to the database.
     */
    public function start(): void
    {
        $this->reply('Чат добавлен в список');
    }

    /**
     * Alias for method `start`
     */
    public function addStockChat(): void
    {
        $this->start();
    }

    /**
     * Send a pickup list to the appropriate chat or present store selection buttons.
     */
    public function pickupList(): void
    {
        if ($this->isPrivateChat()) {
            $this->pickupListForChat($this->chat->id);

            return;
        }

        $buttons = [];
        Stock::query()->with('privateChat:id,chat_id')
            ->where('group_chat_id', $this->chat->id)
            ->each(function (Stock $stock) use (&$buttons) {
                $buttons[] = Button::make("{$stock->name} {$stock->address}")
                    ->action(TelegramBotActions::PICKUP_LIST->value)
                    ->param('chat_id', $stock->privateChat->id);
            });

        $this->chat->message('Выберите магазин:')
            ->keyboard(Keyboard::make()->buttons($buttons))
            ->send();
    }

    /**
     * Send a pickup list to a specified chat or the current chat.
     */
    public function pickupListForChat(?int $chatId = null): void
    {
        $this->replyWebhook();
        $chatId ??= $this->data->get('chat_id');
        $pickupList = $this->inventoryService->pickupList($chatId);
        $this->chat->html($pickupList)->send();
    }

    /**
     * Pause offline order notifications for 30 minutes and send a message about it.
     */
    public function pause30(): void
    {
        $this->pause(30);
    }

    /**
     * Pause offline order notifications for 60 minutes and send a message about it.
     */
    public function pause60(): void
    {
        $this->pause(60);
    }

    /**
     * Pause offline order notifications for 90 minutes and send a message about it.
     */
    public function pause90(): void
    {
        $this->pause(90);
    }

    /**
     * Check if the current chat is a private chat.
     */
    private function isPrivateChat(): bool
    {
        $telegramChat = $this->message?->chat() ?? $this->callbackQuery?->message()?->chat();

        return $telegramChat->type() === 'private';
    }

    /**
     * Reply to a webhook with a specified message if applicable.
     */
    private function replyWebhook(string $message = 'ok'): void
    {
        if (isset($this->callbackQueryId)) {
            $this->reply($message);
        }
    }

    /**
     * Pause offline order notifications for the specified number of minutes and send a message about it.
     */
    private function pause(int $minutes): void
    {
        $pauseUntil = $this->chat->setOfflineNotificationsPause($minutes);
        $message = 'Уведомления по оффлайн заказам отключены до ' . $pauseUntil->format('d.m H:i:s');

        $this->chat->message($message)->send();
    }
}
