<?php
use AkniCallback\Controller\PageView;
$pluginDir = '';
$pageView = PageView::getInstance($pluginDir);
$context = [];
$context['labels'] = [
    'sender_name' => __('Sender Name'),
    'sender_email'=> __('Sender Email'),
    'recipient'   => __('Mail Recipient'),
    'submit_text' => __('Save changes'),
    'success_msg' => __('All changes saved.'),
    'page_title'  => __('Callback Mail Settings')
];
$context['options'] = (array) get_option('akni-callback-settings');
?>

<div class="msgs">
    <?php if ($_REQUEST['settings-updated']):?>
        <span class="success-msg"><?php echo $context['labels']['success_msg'];?></span>
    <?php endif;?>
</div>

<h3><?php echo $context['labels']['page_title'];?></h3>
<form method="post" action="options.php" class="settings-form">
    <?php
    settings_fields('akni-callback-settings');
    $pageView->display('callback-settings.twig', $context);
    ?>
</form>