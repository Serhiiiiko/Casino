<?php

namespace App\Enums;

enum Games2ApiErrorsEnum : string {
    case INVALID_METHOD = 'Неверный метод запроса';
    case INVALID_PARAMETER = 'Неверный параметр запроса';
    case INVALID_AGENT = 'Недействительный агент';
    case INVALID_AGENT_ROLE = 'Недействительная роль агента';
    case BLOCKED_AGENT = "Агент заблокирован";
    case INVALID_USER = "Недействительный пользователь";
    case INSUFFICIENT_AGENT_FUNDS = "Недостаточно средств на счёте агента";
    case INSUFFICIENT_USER_FUNDS = "Недостаточно средств на счёте пользователя";
    case DUPLICATED_USER = "Код пользователя продублирован";
    case INVALID_PROVIDER = "Недействительный провайдер";
    case INTERNAL_ERROR = "Внутренняя ошибка сервера";
    case EXTERNAL_ERROR = "Внешняя ошибка сервера";
    case API_CHECKING = "API в данный момент проверяется";
    case AGENT_SEAMLESS = "Agent Seamless Error";
}
