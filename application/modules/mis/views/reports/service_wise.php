<style>
	h1 {
		color: navy;
		font-family: times;
		font-size: 24pt;
		text-align: center;
	}
	.TFtable {
		font-family: 'Roboto', sans-serif;
		width: 95%;
		margin-bottom: 20px;
		background-color: transparent;
		border-collapse: collapse;
		border-spacing: 0;
		display: table;
	}
	.TFtable tr,
	.TFtable td {
		border: 1px solid #9e9595;
		padding: 10px;
	}
</style>
</style>

<h1 class="title">Status Of RTPS Applications</h1>
<table border="1" cellspacing="0" cellpadding="10" style="border:1px solid #9e9595" class="TFtable">
	<thead>
		<tr>
			<td width="8%">(#)</td>
			<td width="35%">Service Name</td>
			<td style="background-color: #c8edf3;">Total</td>
			<td style="background-color: #fafa9c;">Pending</td>
			<td style="background-color: #fada9e;">Pending In Time</td>
			<td style="background-color: #bdf7cb;">Delivered</td>
			<td style="background-color: #bdf7cb;">Delivered In Time</td>
			<td style="background-color: #ff6663;">Rejected</td>
			<td style="background-color: #fca6a4;">Rejected In Time</td>
		</tr>
	</thead>
	<tbody>
		<?php
		$sl = 1;
		foreach ($data as $val) : ?>
			<tr class="">
				<td width="8%"><?= $sl; ?></td>
				<td width="35%"><?= $val->service_name; ?></td>
				<td style="background-color: #c8edf3;"><?= $val->total_received; ?></td>
				<td style="background-color: #fafa9c;"><?= $val->pending; ?></td>
				<td style="background-color: #fada9e;"><?= $val->pit; ?></td>
				<td style="background-color: #bdf7cb;"><?= $val->delivered; ?></td>
				<td style="background-color: #bdf7cb;"><?= $val->timely_delivered; ?></td>
				<td style="background-color: #ff6663;"><?= $val->rejected; ?></td>
				<td style="background-color: #fca6a4;"><?= $val->rit; ?></td>
			</tr>
		<?php $sl++;
		endforeach ?>
	</tbody>
</table>