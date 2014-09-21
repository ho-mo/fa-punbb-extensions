<?php

/**
 * Language file for pun_attacnment extension
 *
 * @copyright (C) 2008-2012 PunBB, partially based on Attachment Mod by Frank Hagstrom
 * @license http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 * @package pun_attachment
 */

if (!defined('FORUM')) die();

// Language definitions for frequently used strings
$lang_attach = array(
//admin
'Display images'		=>	'نمایش عکس ها',
'Display small'			=>	'تصاویر در تایپک/نمایش می توانند دیده شوند که پارمترهای زیر را داشته باشند:',
'Disable attachments'	=>	'غیر فعال سازی پیوست',
'Display icons' 		=>	'فعال سازی نمایش آیکون ها',
'Create orphans'		=>	'در صورتی که می خواهید یه خط جدا افتاده ایجاد کنید این را فعال کنید.',
'Always deny'			=>	'رد همیشگی.',
'Filesize'				=>	'اندازه فایل',
'Filename'				=>	'نام فایل',
'Max filesize'			=>	'بیشترین اندازه فایل',
'Max height'			=>	'بیشترین ارتفاع',
'Max width'				=>	'بیشترین پهنا',
'Manage icons'			=>	'مدیریت آیکون ها',
'Main options'			=>	'اختیارات اصلی',
'Attachment rules'		=>	'قواعد پیوست',
'Attachment page head'	=>	'پیویست <strong>%s</strong>',
'Delete button'			=>	'حذف',
'Attach button'			=>	'پیوست',
'Rename button'			=>	'تغییر اسم',
'Detach button'			=>	'تفکیک',
'Uploaded date'			=>	'تاریخ بارگذاری',
'MIME-type'				=>	'MIME-type',
'Post id'				=>	'شتاسه پست',
'Downloads'				=>	'دریافت ها',
'New name'				=>	'نام جدید',
'Ascending'				=>	'صعودی',
'Descending'			=>	'نزولی',

'Create orphans'		=>	'orphans ایجاد ',
'Orphans help'			=>	'.اگر این گزینه فعال باشد زمانی که یک کاربر پستی را حذف کند ، پیوست ها از پایگاه داده پاک نمی شوند',
'Icons help'			=>	'آیکون پیوست ها در FORUM_ROOT/extensions/attachment/img/ ذخیره می شوند. برای تغییر یا اضافه کردن آیکون از این ساختار استفاده کنید. در ابتدای ستون ها نوع را وارد کنید سپس اسم آیکون ها را در سلول متناظر قرار دهید ، فرمت های قابل قبول : png, gif, jpeg, ico formats می باشند.',


// la
'Attachment'			=>	'پیوست ها',
'Size:'					=>	'اندازه:',
'bytes'					=>	'بایت',
'Downloads:'			=>	'دریافت ها:',
'Kbytes'				=>	' کیلو بایت',
'Mbytes'				=>	' مگا بایت',
'Bytes'					=>	' بایت',
'Kb'					=>	' kb',
'Mb'					=>	' mb',
'B'						=>	' b',
'Since'					=>	'%s دریافت می شود از %s',
'Never download'		=>	'فایل هرگز دریافت نشده است.',
'Since (title)'			=>	'%s دریافت شده از %s',
'Attachment icon'		=>	'آیکون پیوست',

'Number existing'		=>	'پیوست موجود #<strong>%s</strong>',

//edit.php
'Existing'				=>	'پیوست های موجود: ',	//Used in edit.php, before the existing attachments that you're allowed to delete

//attach.php
'Download:'				=>	'دریافت:',
'Attachment added'		=>	'پیوست افزوده شده ، به طور اتوماتیک هدایت می شوید ... ',
'Attachment delete'		=>	'پیوست حذف شدبه طور اتوماتیک هدایت می شوید ...',

//rules
'Group attach part'		=>	'مجوز پیوست ها',
'Rules'					=>	'قوانین پیویست ها',
'Download'				=>	'اجازه به کاربران برای دریافت فایل ها',
'Upload'				=>	'اجازه به کاربران برای ارسال فایل ها',
'Delete'				=>	'اجازه به کاربران برای حذف فایل ها',
'Owner delete'			=>	'اجازه به کاربران برای حذف فایل های خودشان',
'Size'					=>	'بیشترین اندازه فایل',
'Size comment'			=>	'بیشترین اندازه فایل های آپلود شده (به بیت).',
'Per post'				=>	'تعداد پیوست ها در هر پست',
'Allowed files'			=>	'فایل های مجاز',
'Allowed comment'		=>	'در صورت خالی بودن ، اجازه بدهید تمام فایل به جز آن فایل ها همیشه رد شوند.',
'File len err'			=>	'اسم فایل نمی تواند از ۲۵۵ کارکتر بیشتر باشد.',
'Ext len err'			=>	'پسوند فایل نمی تواند بیشتر از ۶۴ کارکتر باشد.',

// Notices
'Wrong post'			=>	'شماره شناسه پست اشتباه است ، لطفا آن را اصلاح کنید.',
'Too large ini'			=>	'فایل انتخاب شده بزرگتر از آن است که بارگذاری شود.',
'Wrong icon/name'		=>	'پسوند شما اشتباه است.',
'No icons'				=>	'شما داده خالی از پسوند /آیکون وارده کرده اید. لطفا برگرید تا آن را اصلاح کنید.',
'Wrong deny'			=>	'You have entered a wrong list of denied extensions. Please, go back and correct it.',
'Wrong allowed'			=>	'You have entered a wrong list of allowed extensions. Please, go back and correct it.',
'Big icon'				=>	'The icon <strong>%s</strong> is too wide/high. Please, select another one.',
'Missing icons'			=>	'The following icons are missing:',
'Big icons'				=>	'The following icons are too wide/high:',

'Error: mkdir'			=>	'Unable to create new the subfolder with the name',
'Error: 0750'			=>	'with mode 0750',
'Error: .htaccess'		=>	'Unable to copy .htaccess file to the new subfolder with name',
'Error: index.html'		=>	'Unable to copy index.html file to the new subfolder with name',
'Some more salt keywords'	=> 'Some more salt keywords, change if you want to',
'Put salt'				=>	'put your salt here',
'Attachment options'	=>	'Attachment options',
'Rename attachment'		=>	'نام گذاری دوباره پیوست',
'Old name'				=>	'اسم قدیمی',
'New name'				=>	'اسم جدید',
'Input new attachment name'	=>	'Input a new attachment name (without extension)',
'Attachments'			=>	'Attachments',
'Start at'				=>	'Start at',
'Number to show'		=>	'Number to show',
'to'					=>	'به',
'Owner'					=>	'مالک',
'Topic'					=>	'تایپک',
'Order by'				=>	'منظم شدن بر طبق',
'Result sort order'		=>	'نتایح منظم شدن ',
'Orphans'				=>	'جدا افتاده',
'Apply'					=>	'اعمال',
'Show only "Orphans"'	=>	'فقط نمایش ((جدا افتاده)) ها',
'Error creating attachment'	=>	'Error whilecreating attachment, inform the owner of this bulletin board about this problem',
'Use icons'				=>	'Use icons',
'Error while deleting attachment'	=>	'Error while deleting attachment. Attachment is not deleted.',
'Salt keyword'			=>	'Salt keyword, replace if you want to',

'Too short filename'	=>	'Please, enter an unempty filename if you want to rename this attachment.',
'Wrong post id'			=>	'You have entered a wrong post id. Please, correct it if you want to attach a file to this post.',
'Empty post id'			=>	'Please, enter an unempty post id if you want to attach this file to the post.',
'Attach error'			=>	'<strong>Warning!</strong> The following errors must be corrected before you can attach a file:',
'Rename error'			=>	'<strong>Warning!</strong> The following errors must be corrected before you can rename the attachment:',

'Edit attachments'		=>	'ویرایش پیوست ها',
'Post attachments'		=>	'پیوست های پست',
'Image preview'			=>	'پیش نمایش از تصویر',

'Manage attahcments'	=>	'مدیریت پیوست ها',
'Manage id'				=>	'مدیریت پیوست %s',

'Permission denied'		=>	'پوشه "FORUM_ROOT/extensions/pun_attachment/attachments" قابل نوشتن برای سرور نویست.',
'Htaccess fail'			=>	'فایل "FORUM_ROOT/extensions/pun_attachment/attachments/.htaccess" وجود ندارد.',
'Index fail'			=>	'فایل "FORUM_ROOT/extensions/pun_attachment/attachments/index.html" وجود ندارد.',
'Errors notice'			=>	'با این خطا ها مواجه شدیم:',

'Del perm error'		=>	'شما اجازه حذف این فایل را ندارید.',
'Up perm error'			=>	'شما اجازه ی بارگذاری در این پست را ندارید.',

'Attach limit error'	=>	'شما تنها می تونید %s پیوست به این پست اضافه کنید.',
'Ext error'				=>	'امکان پیوست فایل با پسوند "%s" موجود نیست',
'Filesize error'		=>	'امکان آپلود فایل های بیش از  "%s" بایت وجود ندارد.',
'Bad image'				=>	'تصویر دچار مشکل است ، لطفا آن را دوباره بارگذاری کنید.',
'Add file'				=>	'اضافه کردن فایل',
'Post attachs'			=>	'پیوست های پست.',
'Download perm error'	=>	'شما اجازه ی دریافت پیوست های این پست را ندارید.',
'None'					=>	'خالی',

'Id'					=>	'شناسه',
'Owner'					=>	'مالک',
'Up date'				=>	'زمان بارگذاری',
'Type'					=>	'نوع'

);
