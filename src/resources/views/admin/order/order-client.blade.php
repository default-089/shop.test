@if ($order)
    <input type="hidden" value="{{ $order->id }}" id="js-orderId">
    <input type="hidden" name="user_id" value="{{ $order?->user?->id }}" id="js-orderUserId">
    <div id="js-userInfo">
        @if (isset($order->user))
            <p>
                <b>ФИО:</b>
                {{ $order->user?->last_name }}
                {{ $order->user?->first_name }}
                {{ $order->user?->patronymic_name }}
            </p>
            @if ($order->user?->addresses?->first()?->city)
                <p><b>Город:</b> {{ $order->user?->addresses?->first()?->city }}</p>
            @endif
            <p>
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#orderUserChange"
                    aria-expanded="false" aria-controls="orderUserChange">
                    Изменить
                </button>
            </p>
            <div class="collapse" id="orderUserChange">
                <div class="card card-body">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                        <input data-code="BY" required="1" style="width: 250px" type="text" id="userChangePhone"
                            name="userChangePhone" class="form-control js-phone-input" placeholder="Введите телефон">
                    </div>
                    <br>
                    <button class="btn btn-success js-changeOrderUserByPhone">Ок</button>
                </div>
            </div>
        @else
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#js-createOrderUserModal">
                Добавить / создать клиента
            </button>
            <div class="modal fade" id="js-createOrderUserModal" tabindex="-1" role="dialog"
                aria-labelledby="js-createOrderUserModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="js-createOrderUserModalLabel">Создание клиента</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-sm-3 asterisk control-label">Телефон</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                                        <input data-code="BY" required="1" type="text" id="userCreatePhone"
                                            name="userCreatePhone" class="form-control js-phone-input"
                                            placeholder="Введите телефон">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 asterisk control-label">Фамилия</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input required="1" type="text" name="userCreateLastName"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 asterisk control-label">Имя</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input required="1" type="text" name="userCreateFirstName"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Отчество</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input required="1" type="text" name="userCreatePatronymicName"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="button" class="btn btn-primary js-createOrderUser">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
