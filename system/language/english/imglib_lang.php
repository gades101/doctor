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

$lang['imglib_source_image_required'] = 'Ви повинні вказати вихідний файл в налаштуваннях.';
$lang['imglib_gd_required'] = 'Для зображення цієї функції потрібна Бібліотека GD.';
$lang['imglib_gd_required_for_props'] = 'Your server must support the GD image library in order to determine the image properties.';
$lang['imglib_unsupported_imagecreate'] = 'Ваш сервер повинен підтримувати бібліотеку зображень GD для того, щоб визначити властивості зображення.';
$lang['imglib_gif_not_supported'] = 'GIF зображення часто вже не підтримується з-за ліцензійних обмежень. Вам, можливо, доведеться використовувати JPG або PNG зображення.';
$lang['imglib_jpg_not_supported'] = 'JPG зображення не підтримуються.';
$lang['imglib_png_not_supported'] = 'PNG зображення не підтримуються.';
$lang['imglib_jpg_or_png_required'] = 'Протокол зміни розміру зображення, зазначений в настройках працює тільки з типами зображення JPEG або PNG.';
$lang['imglib_copy_error'] = 'Відбулася помилка при спробі замінити файл. Будь ласка, переконайтеся, що ваш каталог вільний для запису.';
$lang['imglib_rotate_unsupported'] = 'Поворот зображення, здається, не підтримується сервером.';
$lang['imglib_libpath_invalid'] = 'Шлях до вашій бібліотеці зображення, що не правильно. Будь ласка, встановіть правильний шлях у налаштуваннях зображень.';
$lang['imglib_image_process_failed'] = 'Обробка зображення не вдалося. Будь ласка, переконайтеся, що ваш сервер підтримує обраний протокол і що шлях до вашої бібліотеці зображень є правильним.';
$lang['imglib_rotation_angle_required'] = 'Кут повороту потрібно, щоб повернути зображення.';
$lang['imglib_invalid_path'] = 'Шлях до зображення не є правильним.';
$lang['imglib_copy_failed'] = 'Не вдалося виконати процес копіювання зображення.';
$lang['imglib_missing_font'] = 'Не можу знайти шрифт.';
$lang['imglib_save_failed'] = 'Неможливо зберегти зображення. Будь ласка, переконайтеся, що каталог зображень і файлів є доступним для запису.';