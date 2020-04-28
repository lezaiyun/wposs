<?php
/**
 *  插件设置页面
 * User: zdl25
 * Date: 2019/3/15
 * Time: 17:43
 */
function wposs_setting_page() {
    // 如果当前用户权限不足
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient privileges!');
    }

	$wposs_options = wposs_check_options(get_option('wposs_options'));

    if ($wposs_options && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
        if($_POST['type'] == 'info_set') {
            foreach ($wposs_options as $k => $v) {
                if ($k =='no_local_file') {
                    $wposs_options[$k] = (isset($_POST[$k])) ? 'true' : 'false';
                } elseif ($k != 'upload_information') {
	                $wposs_options[$k] = (isset($_POST[$k])) ? sanitize_text_field(trim(stripslashes($_POST[$k]))) : '';
                }
            }

            # 更新另外两个wp自带的上传相关属性的值
            # 替换 upload_path 的值
            $upload_path = sanitize_option('upload_path', trim(trim(stripslashes($_POST['upload_path'])), '/'));
            update_option('upload_path', ($upload_path == '') ? ('wp-content/uploads') : ($upload_path));
	        # 替换 upload_url_path 的值
	        update_option('upload_url_path', esc_url_raw(trim(trim(stripslashes($_POST['upload_url_path']))), '/'));

	        $wposs_options['upload_information']['active'] = array(
	            'upload_path' => ($upload_path == '') ? 'wp-content/uploads' : $upload_path,
	            'upload_url_path' => esc_url_raw(trim(trim(stripslashes($_POST['upload_url_path']))), '/'),
            );

	        // 不管结果变没变，有提交则直接以提交的数据 更新 wposs_options
	        update_option('wposs_options', $wposs_options);
?>
   <div class="notice notice-success settings-error is-dismissible"><p><strong>设置已保存。</strong></p></div>

<?php

    }
}

?>


<div class="wrap">
    <h1 class="wp-heading-inline">WordPress OSS（WPOSS）阿里云OSS设置</h1> <a href="https://www.laobuluo.com/2250.html" target="_blank"class="page-title-action">插件介绍</a>
        <hr class="wp-header-end">        
        <p>WordPress OSS（简称:WPOSS），基于阿里云OSS对象存储与WordPress实现静态资源到OSS存储中。提高网站项目的访问速度，以及静态资源的安全存储功能。</p>
        <p>快速导航：<a href="https://www.laobuluo.com/aliyun/" target="_blank">最新阿里云优惠汇总</a> / 站长QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5IpUNWK" target="_blank"> <font color="red">1012423279</font></a>（交流建站和运营） / 公众号：QQ69377078（插件反馈）</p>
                 
      <hr/>
    <form name="form1" method="post" action="<?php echo wp_nonce_url('./admin.php?page=' . WPOSS_BASEFOLDER . '/wposs_actions.php'); ?>">
        <table class="form-table">
            <tr>
                <th scope="row">
                       Bucket 名称
                    </th>
               
                <td>
                    <input type="text" name="bucket" value="<?php echo esc_attr($wposs_options['bucket']); ?>" size="50"
                           placeholder="BUCKET"/>

                     <p>我们需要在 <a href="https://oss.console.aliyun.com/overview" target="_blank">阿里云OSS控制台</a> 创建
                        <code>Bucket</code> ，再填写以上内容。 比如：laobuluocom</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                      EndPoint 地域节点
                    </th>
                
                <td>
                    <input type="text" name="endpoint" value="<?php echo esc_attr($wposs_options['endpoint']); ?>" size="50"
                           placeholder="oss-cn-shanghai.aliyuncs.com"/>
                    <p>1. 我们在创建Bucket之后，在[概况]中，可以看到 EndPoint 地域节点</p>
                    <p>2. 如果我们的WordPress部署在非阿里云服务器，则输入[外网访问]对应的EndPoint节点</p>
                    <p>3. 如果使用的是[ECS 的经典网络访问]或者[ECS 的 VPC 网络访问]则对应的EndPoint节点</p>
                </td>
            </tr>
            <tr>
                 <th scope="row">
                      Access Key Id
                    </th>
                
                <td><input type="text" name="accessKeyId" value="<?php echo esc_attr($wposs_options['accessKeyId']); ?>" size="50" placeholder="AccessKeyId"/></td>
            </tr>
            <tr>
                <th scope="row">
                  Access Key Secret
                    </th>
               
                <td>
                    <input type="text" name="accessKeySecret" value="<?php echo esc_attr($wposs_options['accessKeySecret']); ?>" size="50" placeholder="AccessKeySecret"/>
                    <p> Access Key API需要我们参考老部落介绍的教程中获取当前账户的API信息( <a href="https://www.laobuluo.com/2228.html" target="_blank">参考文章地址</a>)，然后填写。</p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                  不在本地保留备份
                    </th>
               
               
                <td>
                    <input type="checkbox"
                           name="no_local_file" <?php if (esc_attr($wposs_options['no_local_file']) == 'true') {
						echo 'checked="TRUE"';
					}
					?> />

                   <p>如果我们只需要将图片等静态文件上传放置OSS中，则勾选；如果我们本地和OSS都存储，那就不勾选。</p>
                </td>
            </tr>
            <tr>
                 <th scope="row">
                  本地文件夹
                    </th>
                
                <td>
                    <input type="text" name="upload_path" value="<?php echo esc_attr(get_option('upload_path')); ?>" size="30"
                           placeholder="本文文件夹 默认是 wp-content/uploads"/>

                    <p>1. 附件在服务器上相对于WordPress根目录的存储位置，例如： <code>wp-content/uploads</code> （注意不要以“/”开头和结尾），根目录请输入<code>.</code>。</p>
                    <p>2. <b><font color="red">建议默认：</font></b> <code>wp-content/uploads</code></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                Bucket域名+本地文件夹路径
                    </th>
                
                <td>
                    <input type="text" name="upload_url_path" value="<?php echo esc_url(get_option('upload_url_path')); ?>" size="60"
                           placeholder="请输入Bucket 域名+本地文件夹"/>

                  <p><b>设置注意事项：</b></p>
                    <p>1. URL前缀的格式为 <code>{http或https}://{bucket} 域名地址</code>/<code><font color="red">本地文件夹</font></code></p>
                    <p>2. 示范A： <code>https://laobuluo.oss-cn-shanghai.aliyuncs.com/<font color="red">wp-content/uploads</font></code></p>
                    <p>3. 示范B： <code>http://oss.laobuluo.com/<font color="red">wp-content/uploads</font></code></p>
                </td>
            </tr>
            <tr>
                <th>
                   
                </th>
                <td><input type="submit" name="submit" value="保存设置" class="button button-primary"/></td>
            </tr>
        </table>
        <input type="hidden" name="type" value="info_set">
    </form>
     <hr>
        <div style='text-align:center;line-height: 50px;'>
            <a href="https://www.laobuluo.com/" target="_blank">插件主页</a> | <a href="https://www.laobuluo.com/2250.html" target="_blank">插件发布页面</a> | <a href="https://jq.qq.com/?_wv=1027&k=5IpUNWK" target="_blank">QQ群：1012423279</a> | 公众号：QQ69377078（插件反馈）

        </div>
</div>
<?php
}
?>
