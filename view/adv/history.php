<?php
if(!empty($data['adv'])){
    print "<p>Адрес: " . $data['adv']['address'] . "</p>";
    print "<p>Имя: " . $data['adv']['owner_name'] . "</p>";
    print "<p>Телефон: " . $data['adv']['owner_phone'] . "</p>";
}
?>

<?php if(!empty($data['history'])){?>
    <div class="table-responsive" style="margin-top: 50px;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Результат модерации</th>
                <th>Правило</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data['history'] as $history){?>
                <tr>
                    <td>
                        <?=(!empty($data['status'][$history['status']]))?$data['status'][$history['status']]:'Код статуса: ' . $history['status']?>
                    </td>
                    <td>
                        <?=(!empty($history['rule_id']))?$history['description'] . " ({$history['name']})":''?>
                    </td>
                    <td><?=$history['date']?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }else{
    print "<h2>История модерации пуста</h2>";
}?>