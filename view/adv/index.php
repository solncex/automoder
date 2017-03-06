<a href="/adv/edit/" class="btn btn-default">Создать новый опрос</a>

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
                <th></th>
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
                        <a href="/adv/edit/?id=<?=$adv['id']?>" class='btn btn-link'>изменить</a>
                        <a href="/adv/delete/?id=<?=$adv['id']?>" class='btn btn-link'>Удалить</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }?>