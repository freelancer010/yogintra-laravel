<div class="datalist">
    <table id="example1" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th><?= ('User') ?></th>
                <th><?= ('Username') ?></th>
                <th><?= ('Email') ?></th>
                <th><?= ('Role') ?></th>
                <th width="100"><?= ('Status') ?></th>
                <th width="120"><?= ('Action') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($info as $row): ?>
            <tr>
                <td>
					<h4 class="m0 mb5"><?=$row['firstname']?> <?=$row['lastname']?></h4>
                    <small class="text-muted"><?=$row['admin_role_title']?></small>
                </td>
                <td>
                    <?=$row['username']?>
                </td> 
                <td>
					<?=$row['email']?>
                </td>
                <td>
                    <button class="btn btn-xs btn-success"><?=$row['admin_role_title']?></button>
                </td> 
                <td><input class='tgl tgl-ios tgl_checkbox' 
                    data-id="<?=$row['admin_id']?>" 
                    id='cb_<?=$row['admin_id']?>' 
                    type='checkbox' <?php echo ($row['is_active'] == 1)? "checked" : ""; ?> />
                    <label class='tgl-btn' for='cb_<?=$row['admin_id']?>'></label>
                </td>
                <td>
                    <a href="<?= base_url("admin/edit/".$row['admin_id']); ?>" class="btn btn-warning btn-xs mr5" >
                    <i class="fa fa-edit"></i>
                    </a>
                    <a href="<?= base_url("admin/delete/".$row['admin_id']); ?>" onclick="return confirm('are you sure to delete?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>
</div>

