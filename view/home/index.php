
<?php if(!empty($data['adv'])){?>
    <div class="table-responsive" style="margin-top: 50px;">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Адрес</th>
                <th>Имя собственника</th>
                <th>Телефон собственника</th>
                <th>Цена</th>
                <th>Результат модерации</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($data['adv'] as $adv){?>
                <tr>
                    <td><?=$adv['id']?></td>
                    <td><?=$adv['address']?></td>
                    <td><?=$adv['owner_name']?></td>
                    <td><?=$adv['owner_phone']?></td>
                    <td><?=$adv['price']?></td>
                    <td>
                        <a href="/adv/history/?id=<?=$adv['id']?>" class='btn btn-link'>
                            <?=(!empty($data['status'][$adv['status']]))?$data['status'][$adv['status']]:'Код статуса: ' . $adv['status']?>
                        </a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }?>