<?php
View::show_header('Client Rules');

if (!$WhitelistedClients = $Cache->get_value('whitelisted_clients')) {
    $DB->query('
        SELECT vstring, peer_id
        FROM xbt_client_whitelist
        WHERE vstring NOT LIKE \'//%\'
        ORDER BY vstring ASC');
    $WhitelistedClients = $DB->to_array(false, MYSQLI_NUM, false);
    $Cache->cache_value('whitelisted_clients', $WhitelistedClients, 604800);
}
?>
<div class="thin">
<?php include('jump.php'); ?>
    <div class="header">
        <h2 class="general">Client Whitelist</h2>
    </div>
    <div class="box pad">
        <p>Client rules are how we maintain the integrity of our swarms. This allows us to filter out disruptive and dishonest clients that may hurt the performance of either the tracker or individual peers.</p>
        <table cellpadding="5" cellspacing="1" border="0" class="border" width="100%">
            <tr class="colhead">
                <td><strong>Allowed Client</strong></td>
                <td style="width: 75px"><strong>Peer ID</strong></td>
                <!-- td style="width: 400px;"><strong>Additional Notes</strong></td> -->
            </tr>
<?php
    $Row = 'a';
    foreach ($WhitelistedClients as $Client) {
        //list($ClientName, $Notes) = $Client;
        list($ClientName, $PeerID) = $Client;
        if (strpos($ClientName, 'HIDDEN') !== false) {
            continue;
        }
        $Row = $Row === 'a' ? 'b' : 'a';
?>
            <tr class="row<?=$Row?>">
                <td><?=$ClientName?></td>
                <td><?=$PeerID?></td>
            </tr>
<?php
    } ?>
        </table>
    </div>
</div>
<?php View::show_footer(); ?>
