<?php

namespace Mrjokermr\DynamicModalWrapper\Enums;

enum ModalEvent: string
{
    case OPEN = 'dynamic_modal_event_open';
    case CLOSE = 'dynamic_modal_event_close';
}
