<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.css"> 

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<!-- For Messages -->
		<div class="card">
			<div class="card-header">
				<div class="d-inline-block">
					<h3 class="card-title"><i class="fa fa-list"></i>&nbsp; <?= $title ?></h3>
				</div>
				<div class="d-inline-block float-right">
					<a href="<?= PANELURL.('admin_roles/module_add'); ?>" class="btn btn-success"><i class="fa fa-plus"></i> <?= ('add_new_module') ?></a>
				</div>
			</div>

			<div class="card-body">
				<table id="example1" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th width="50"><?= ('id') ?></th>
							<th><?= ('module_name') ?></th>
							<th><?= ('controller_name') ?></th>
							<th><?= ('fa_icon') ?></th>
							<th><?= ('operations') ?></th>
							<th><?= ('sub_module') ?></th>
							<th width="100"><?= ('action') ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($records as $record): ?>
							<tr>
								<td><?= $record['module_id']; ?></td>
								<td><?= ($record['module_name']); ?></td>
								<td><?= $record['controller_name']; ?></td>
								<td><?= $record['fa_icon']; ?></td>
								<td><?= $record['operation']; ?></td>
								<td>
									<a href="<?= PANELURL.('admin_roles/sub_module/'.$record['module_id']) ?>" class="btn btn-info btn-xs mr5">
										<i class="fa fa-eye"></i>
									</a>
								</td>
								<td>
									<a href="<?= PANELURL."admin_roles/module_edit/".$record['module_id'] ?>" class="btn btn-warning btn-xs mr5" >
											<i class="fa fa-edit"></i>
										</a>
									<a href="<?= PANELURL."admin_roles/module_delete/".$record['module_id'] ?>" onclick="return confirm('are you sure to delete?')" class="btn btn-danger btn-xs">
									<i class="fa fa-trash"></i></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/dataTables.bootstrap4.js"></script>

<script>
  $(function () {
    $("#example1").DataTable();
  })
</script>