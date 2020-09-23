<?php
require_once(dirname(dirname(dirname(dirname(__FILE__))))."/wp-load.php");
require_once "PHPExcel.php";

if (isset($_POST['export'])) {
	$query = new WP_Query(
		[
			'post_type'      => 'contact', 
			'post_status'    => 'publish', 
			'posts_per_page' => -1,
			'order'          => 'DESC',
			'orderby'        => 'meta_value',
			'meta_query'     => [
				'key'          => 'submitted',
				'meta_value'   => date("m/d/Y hh:mm:ss a")
			]
		]
	);
	
	if ($query->have_posts()) {
		$obj    = new PHPExcel();
		$sheet1 = $obj->getActiveSheet();
		$sheet1->setTitle("Contacts");
		$col = 0;
		$row = 2;
		$chars = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
		
		while ($query->have_posts()) {
			$query->the_post(); 
			$fields  = get_field_objects();
			
			foreach($fields as $field) {
				if ($row == 2) {
					$sheet1->setCellValueByColumnAndRow($col, 1, $field['label']);
				}
				
				$sheet1->setCellValueByColumnAndRow($col, $row, $field['value']);				
				$col += 1;
			}
			
			$col  = 0;
			$row += 1;
		}
		
		$last_col = $chars[count($fields)-1];
		$last_row = $row-1;
		
		$sheet1->getStyle("A1:{$last_col}1")->getFont()->setBold(true);
		
		$sheet1->getStyle("A2:{$last_col}{$last_row}")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
		$sheet1->getStyle("{$last_col}2:{$last_col}{$last_row}")->getNumberFormat()->setFormatCode("mm/dd/yyyy hh:mm:ss a/p\m");
		
		foreach($chars as $char) {
			$sheet1->getColumnDimension($char)->setAutoSize(true);
			
			if ($char == $last_col) {
				break;
			}
		}
		
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment;filename=contacts.xlsx");
		header("Cache-Control: max-age=0");

		$writer = PHPExcel_IOFactory::createWriter($obj, "Excel2007");
		$writer->save("php://output");
	}
}
?>