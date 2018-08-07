<?php
$this->log('Importando anexos dos posts...');
$results = $this->get_results('attachments');
$server_base_url = "http://lab.redehumanizasus.net/";
foreach ($results as $attachment)
{
    $url = $server_base_url . $attachment['filepath'];
    $this->insert_attachment_from_url($url, $attachment['id']);
}
$this->log('Anexos importados');