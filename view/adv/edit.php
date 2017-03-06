<?php
if(!empty($data['message'])){
print "<p class='bg-danger'>{$data['message']}</p>";
}
?>

<form method="POST" action="/adv/edit/<?=(!empty($data['adv']['id']))?"?id={$data['adv']['id']}":''?>" class="edit-poll">
    <div class="form-group">
        <label for="address">Адрес</label>
        <input type="text" name="adv[address]" value="<?=(!empty($data['adv']['address']))?$data['adv']['address']:''?>" class="form-control" id="address">
    </div>

    <div class="form-group">
        <label for="name">Имя собственника</label>
        <input type="text" name="adv[owner_name]" value="<?=(!empty($data['adv']['owner_name']))?$data['adv']['owner_name']:''?>" class="form-control" id="name">
    </div>

    <div class="form-group">
        <label for="phone">Телефон собственника</label>
        <input type="text" name="adv[owner_phone]" value="<?=(!empty($data['adv']['owner_phone']))?$data['adv']['owner_phone']:''?>" class="form-control" id="phone">
    </div>

    <div class="form-group">
        <div class="radio">
            <label>
                <input type="radio" name="adv[type]" value="sale" <?=(!empty($data['adv']['type']) && $data['adv']['type']=='sale')?'checked="checked"':''?>>
                Продажа
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="adv[type]" value="rent" <?=(!empty($data['adv']['type']) && $data['adv']['type']=='rent')?'checked="checked"':''?>>
                Аренда
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="price">Цена</label>
        <input type="number" name="adv[price]" value="<?=(!empty($data['adv']['price']))?$data['adv']['price']:''?>" class="form-control" id="price">
    </div>

    <div class="form-group">
        <div class="radio">
            <label>
                <input type="radio" name="adv[owner_type]" value="owner" <?=(!empty($data['adv']['owner_type']) && $data['adv']['owner_type']=='owner')?'checked="checked"':''?>>
                Собственник
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="adv[owner_type]" value="realtor" <?=(!empty($data['adv']['owner_type']) && $data['adv']['owner_type']=='realtor')?'checked="checked"':''?>>
                Риэлтор
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="floor">Этаж</label>
        <input type="number" name="adv[floor]" value="<?=(!empty($data['adv']['floor']))?$data['adv']['floor']:''?>" class="form-control" id="floor">
    </div>

    <div class="form-group">
        <label for="storeys">Этажность</label>
        <input type="number" name="adv[storeys]" value="<?=(!empty($data['adv']['storeys']))?$data['adv']['storeys']:''?>" class="form-control" id="storeys">
    </div>

    <div class="form-group">
        <label for="rooms">Число комнат</label>
        <input type="number" name="adv[rooms]" value="<?=(!empty($data['adv']['rooms']))?$data['adv']['rooms']:''?>" class="form-control" id="rooms">
    </div>

    <div class="form-group">
        <label for="area">Общая площадь</label>
        <input type="number" name="adv[area]" value="<?=(!empty($data['adv']['area']))?$data['adv']['area']:''?>" class="form-control" id="area">
    </div>

    <div class="form-group">
        <label for="description">Описание</label>
        <textarea class="form-control" name="adv[description]" id="description" rows="10"><?=(!empty($data['adv']['description']))?$data['adv']['description']:''?></textarea>
    </div>

    <div class="form-group">
        <label for="source">Источник</label>
        <select class="form-control" name="adv[source_id]" id="source">
            <?php
                if(!empty($data['sources'])){
                    foreach ($data['sources'] as $source) {
                        $selected = (!empty($data['adv']['source_id']) && $data['adv']['source_id']==$source['id'])?'selected':'';
                        print "<option value='{$source['id']}' $selected>{$source['name']}</option>";
                    }
                }
            ?>
        </select>
    </div>

    <button type="submit" class="btn btn-default">Сохранить</button>
    <span class="help-block"></span>
</form>