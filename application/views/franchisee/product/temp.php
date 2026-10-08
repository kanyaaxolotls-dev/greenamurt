
<?php
	$Total = 0;
	$i = 0;
	foreach($record as $val)
	{		
		$i++;
		 $Total1 += $val['payable_amount'];
		$product = $this->db->get_where("temp", array('id' => $val['product']))->result_array();
		 $name= $this->db->get_where("product", array('id' => $product[0]['product']))->result_array();
		//var_dump($name[0]['prod_name']);die();
?>	
	
<tr>	
	<td><?php echo $i;?></td>													
	<td><?php echo $name[0]['prod_name'] ?></td>
	<td><?php echo $val['quantity'];?></td>													
	<td><?php echo $val['price'];?></td>													
													
	<td><?php echo $val['gst_per'];?></td>													
	<td><?php echo $val['payable_amount'];?></td>	
	<!-- <td><?php //echo $val['note'];?></td>	 -->	
	<td width="5%">
		<a href="javascript:delete_quotation_temp(<?php echo $val['id'];?>)" id="<?php echo $val['id'];?>" ><img border="0" src="<?php  echo base_url();?>/uploads/delete.jpg" style="height: 20px;"></a> 
	</td> 
</tr>

<?php
	}
?>
<tr>												
	<td></td>
	<td></td>
	<td></td>
	<td></td>
													
	<td><b>Total</b></td>			

	<?php
		$Total = number_format((float)$Total1, $decimal_point, '.', '');

	?>
	
	<td><b><?php echo $Total;?></b></td>												
	<td></td>
	
</tr>