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

	$wposs_options = get_option('wposs_options', True);

    if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce']) && !empty($_POST)) {
        if($_POST['type'] == 'info_set') {

            foreach ($wposs_options as $k => $v) {
                if ($k =='no_local_file') {
                    $wposs_options[$k] = (isset($_POST[$k])) ? 'true' : 'false';
                } else {
                    $wposs_options[$k] = (isset($_POST[$k])) ? sanitize_text_field(trim(stripslashes($_POST[$k]))) : '';
                }
            }

            // 不管结果变没变，有提交则直接以提交的数据 更新 wposs_options
            update_option('wposs_options', $wposs_options);

            # 更新另外两个wp自带的上传相关属性的值
            # 替换 upload_path 的值
            $upload_path = sanitize_option('upload_path', trim(trim(stripslashes($_POST['upload_path'])), '/'));
            update_option('upload_path', ($upload_path == '') ? ('wp-content/uploads') : ($upload_path));

            # 替换 upload_url_path 的值
            update_option('upload_url_path', esc_url_raw(trim(trim(stripslashes($_POST['upload_url_path']))), '/'));

?>
    <div class="updated"><p><strong>设置已保存！</strong></p></div>

<?php

    }
}

?>


<div class="wrap" style="margin: 10px;">
    <h2>WordPress OSS（WPOSS）阿里云OSS设置</h2>
     <hr/>    
        <p>WordPress OSS（简称:WPOSS），基于阿里云OSS对象存储与WordPress实现静态资源到OSS存储中。提高网站项目的访问速度，以及静态资源的安全存储功能。</p>
        <p>插件网站： <a href="https://www.laobuluo.com" target="_blank">老部落</a> / <a href="https://www.laobuluo.com/2186.html" target="_blank">WPOSS插件发布页面和安装设置教程</a> / 站长交流QQ群： <a href="https://jq.qq.com/?_wv=1027&k=5gBE7Pt" target="_blank"> <font color="red">594467847</font></a>（宗旨：多做事，少说话，效率至上）</p>
        <p>商家促销： <a href="https://www.laobuluo.com/aliyun/" target="_blank">最新阿里云优惠汇总</a></p>
          
      <hr/>
    <form name="form1" method="post" action="<?php echo wp_nonce_url('./admin.php?page=' . WPOSS_BASEFOLDER . '/wposs_actions.php'); ?>">
        <table class="form-table">
            <tr>
                <th>
                    <legend>Bucket 名称</legend>
                </th>
                <td>
                    <input type="text" name="bucket" value="<?php echo esc_attr($wposs_options['bucket']); ?>" size="50"
                           placeholder="BUCKET"/>

                     <p>我们需要在 <a href="https://oss.console.aliyun.com/overview" target="_blank">阿里云OSS控制台</a> 创建
                        <code>Bucket</code> ，再填写以上内容。 比如：laobuluocom</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>EndPoint 地域节点</legend>
                </th>
                <td>
                    <input type="text" name="endpoint" value="<?php echo esc_attr($wposs_options['endpoint']); ?>" size="50"
                           placeholder="http://oss-cn-shanghai.aliyuncs.com"/>
                    <p>请打开 <code>https://help.aliyun.com/document_detail/31837.html</code> 查看OSS所属地域对应的EndPoint。 </p>
                    <p>若您的wordpress部署在非阿里云服务器请选择 <a href="https://help.aliyun.com/document_detail/31837.html#h2-url-1">外网EndPoint</a> (对应表格第3列); </p>
                    <p>若部署在阿里云不同VPC下使用 <a href="https://help.aliyun.com/document_detail/31837.html#h2-url-1">内网EndPoint</a> (对应表格第5列); </p>
                    <p>若部署在同一VPC可使用 <a href="https://help.aliyun.com/document_detail/31837.html#h2-url-2">VPC EndPoint</a> (对应表格第3列)。</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>Access Key Id</legend>
                </th>
                <td><input type="text" name="accessKeyId" value="<?php echo esc_attr($wposs_options['accessKeyId']); ?>" size="50" placeholder="AccessKeyId"/></td>
            </tr>
            <tr>
                <th>
                    <legend>Access Key Secret</legend>
                </th>
                <td>
                    <input type="text" name="accessKeySecret" value="<?php echo esc_attr($wposs_options['accessKeySecret']); ?>" size="50" placeholder="AccessKeySecret"/>
                    <p> Access Key API需要我们参考老部落介绍的教程中获取当前账户的API信息( <a href="https://www.laobuluo.com/2228.html" target="_blank">参考文章地址</a>)，然后填写。</p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>不在本地保留备份</legend>
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
                <th>
                    <legend>本地文件夹</legend>
                </th>
                <td>
                    <input type="text" name="upload_path" value="<?php echo esc_attr(get_option('upload_path')); ?>" size="50"
                           placeholder="请输入上传文件夹"/>

                    <p>1. 附件在服务器上相对于WordPress根目录的存储位置，例如： <code>wp-content/uploads</code> （注意不要以“/”开头和结尾），根目录请输入<code>.</code>。</p>
                    <p>2. 示范：<code>wp-content/uploads</code></p>
                </td>
            </tr>
            <tr>
                <th>
                    <legend>Bucket 域名</legend>
                </th>
                <td>
                    <input type="text" name="upload_url_path" value="<?php echo esc_url(get_option('upload_url_path')); ?>" size="50"
                           placeholder="请输入Bucket 域名"/>

                  <p><b>设置注意事项：</b></p>

                    <p>1）URL前缀的格式为 <code>{http或https}://{bucket}.{外网EndPoint}</code> （“本地文件夹”为 <code>.</code> 时），或者 <code>http://{cos域名}/{本地文件夹}</code>
                        ，“本地文件夹”务必与上面保持一致（结尾无 <code>/</code> ）。</p>

                    <p>2）OSS中的存放路径（即“文件夹”）与上述 <code>本地文件夹</code> 中定义的路径是相同的（出于方便切换考虑）。</p>

                    <p>3）如果需要使用 <code>独立域名</code> ，直接将 <code>{bucket}.{外网EndPoint}</code> 替换为 <code>您的独立域名</code> ，并在OSS控制台域名管理里面<code>绑定该域名</code>。</p>
                </td>
            </tr>
            <tr>
                <th>
                   
                </th>
                <td><input type="submit" name="submit" value="保存WPOSS设置"/></td>
            </tr>
        </table>
        <input type="hidden" name="type" value="info_set">
    </form>
</div>
<?php
}
?>