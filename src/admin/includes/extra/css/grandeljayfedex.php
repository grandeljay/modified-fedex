<?php

namespace Grandeljay\Fedex;

$page_is_fedex =  \defined('FILENAME_MODULES') && \FILENAME_MODULES === \basename($PHP_SELF)
               && isset($_GET['set'], $_GET['module'], $_GET['action'])
               && 'shipping' === $_GET['set']
               && \grandeljayfedex::class === $_GET['module']
               && 'edit' === $_GET['action'];

if (!$page_is_fedex) {
    return;
}

$filename = 'includes/css/grandeljay_fedex.css';
$version  = hash_file('crc32c', DIR_FS_ADMIN . $filename);
?>
<link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_ADMIN . $filename ?>?v=<?php echo $version ?>" />
