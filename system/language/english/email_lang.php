<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['email_must_be_array'] = 'Метод перевірки електронної пошти повинен бути переданий як масив.';
$lang['email_invalid_address'] = 'Невірна адреса електронної пошти: %s';
$lang['email_attachment_missing'] = 'Не вдалося знайти наступне вкладення електронної пошти: %s';
$lang['email_attachment_unreadable'] = 'Неможливо відкрити це вкладення: %s';
$lang['email_no_from'] = 'Не можу відправити пошту без "Від" заголовка.';
$lang['email_no_recipients'] = 'Ви повинні включити одержувачів: Кому, Копія або Прихована копія';
$lang['email_send_failure_phpmail'] = 'Неможливо відправити лист за допомогою PHP пошти (). Ваш сервер не може бути налаштований для відправки пошти за допомогою цього методу.';
$lang['email_send_failure_sendmail'] = 'Неможливо відправити електронну пошту, використовуючи PHP Sendmail. Ваш сервер не може бути налаштований для відправки пошти за допомогою цього методу.';
$lang['email_send_failure_smtp'] = 'Неможливо відправити електронну пошту, використовуючи PHP SMTP. Ваш сервер не може бути налаштований для відправки пошти за допомогою цього методу.';
$lang['email_sent'] = 'Ваше повідомлення було успішно надіслано, використовуючи наступний протокол: %s';
$lang['email_no_socket'] = 'Неможливо відкрити сокет для Sendmail. Будь ласка, перевірте налаштування.';
$lang['email_no_hostname'] = 'Ви не вказали ім\'я хоста SMTP.';
$lang['email_smtp_error'] = 'У наступному SMTP сталася помилка: %s';
$lang['email_no_smtp_unpw'] = 'Помилка .: Ви повинні призначити SMTP логін і пароль';
$lang['email_failed_smtp_login'] = 'Не вдалося відправити команду AUTH LOGIN. Помилка: %s';
$lang['email_smtp_auth_un'] = 'Не вдалося перевірити справжність користувача. Помилка: %s';
$lang['email_smtp_auth_pw'] = 'Не вдалося перевірити справжність пароль. Помилка: %s';
$lang['email_smtp_data_failure'] = 'Не вдалося відправити дані: %s';
$lang['email_exit_status'] = 'Код стану Вихід: %s';