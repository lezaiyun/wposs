=== WPOSS阿里云对象存储 ===
Contributors: laobuluo
Donate link: https://www.laobuluo.com/donate/
Tags:阿里云oss,oss,对象存储,wordpress oss
Requires at least: 4.5.0
Tested up to: 5.4.1
Stable tag: 1.0.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WordPress OSS（简称:WPOSS），基于阿里云OSS对象存储与WordPress实现静态资源到OSS存储。

== Description ==

<strong>基于WordPress程序，可以选择本地+OSS存储静态资源或者单独使用OSS存储，可以自定义域名或者是使用阿里云自带的Bucket域名。</strong>

<strong>主要功能：</strong>

* 1、基于WordPress程序且免费提供给用户使用，将网站的静态文件，比如图片、附件，选择存储在阿里云OSS中或者同时在本地和OSS中，提高网站加载速度；
* 2、我们可选择使用自定义域名，以及支持HTTPS，前提是我们已经在阿里云OSS中设置完毕。
* 3、插件更多详细介绍和安装：https://www.laobuluo.com/2250.html
* 4、QQ交流群：1012423279


<strong>支持网站/博主：</strong>
* 1、支持网站：https://www.itbulu.com/ （老蒋部落）

== Installation ==

* 1、把wposs文件夹上传到/wp-content/plugins/目录下<br />
* 2、在后台插件列表中激活wposs<br />
* 3、在“WPOSS设置”菜单中输入阿里云OSS云存储相关信息和API信息<br />
* 4、我们可以在编辑文章的时候将静态资源上传到阿里云OSS以及本地备份。

== Frequently Asked Questions ==

* 1.当发现插件出错时，开启调试获取错误信息。
* 2.我们可以选择备份OSS或者本地同时备份。
* 3.支持HTTPS以及自定义域名。

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png

== Changelog ==

= 0.1 =
* 1、WPOSS正式发布。
* 2、本插件经过几周的测试，支持最新的WordPress程序，现予以发布。

= 0.2 =
* 1、根据WP官方发布要求进行修改函数匹配和安全。
* 2、第一次提交WP官方平台，需要修改适配WP官方插件要求。

= 0.3 =
* 1、修复"停用"和"插件"后恢复原WP的目录文件夹
* 2、解决用户安装与主题冲突空白问题

= 1.0.1 =
* 1、通过1年测试WPCOS比较稳定正式版本发布
* 2、重构CSS样式极简风格
* 3、兼容WordPress5.4版本
* 4、计划重构核心代码提高上传速度

= 1.0.2 =
* 1、兼容WordPress5.4.1测试

== Upgrade Notice ==
* 