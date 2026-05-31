<?php
$self_name = 'GoatsInfo';

class GoatsInfo {
	public function table_main($APN, $DEL = no) {
		$sql = "SELECT
			Di.`ava`,
			A.`id` as id,
			A.`name` as name,
			A.`id_farm`,
			A.`is_reg`,

			B.`alias` as breed,
			A.`sex`,

			Di.`is_abg`,
			H.`name` as farm_name,
			Di.`manuf`,
			Di.`owner`,
			Di.`date_born`,
			Di.`born_weight`,
			Di.`born_qty`,
			Di.`horns_type`,
			Di.`have_gen`,
			Di.`gen_mat`,

			A.`id` as reg_code,
			Di.`code_ua` as code_ua,
			Di.`code_abg` as code_abg,
			Di.`code_farm` as code_farm,
			Di.`code_chip` as code_chip,
			Di.`code_int` as code_int,
			Di.`code_brand` as have_brand,
			Di.`cert_serial`,
			Di.`cert_no`,

			M.`id` as id_m,
			M.`name` as m_name,
			M.`id` as m_reg_code,
			Dm.`code_ua` as m_code_ua,
			Dm.`code_abg` as m_code_abg,
			Dm.`code_farm` as m_code_farm,
			Dm.`code_chip` as m_code_chip,
			Dm.`code_int` as m_code_int,
			Dm.`code_brand` as m_have_brand,

			F.`id` as id_f,
			F.`name` as f_name,
			F.`id` as f_reg_code,
			Df.`code_ua` as f_code_ua,
			Df.`code_abg` as f_code_abg,
			Df.`code_farm` as f_code_farm,
			Df.`code_chip` as f_code_chip,
			Df.`code_int` as f_code_int,
			Df.`code_brand` as f_have_brand,

			T.`test_type`,
			T.`score_total`,
			T.`par_1`,
			T.`par_2`,
			T.`par_3`,
			T.`par_4`,
			T.`class`,
			T.`category`,

			L.`viewer`,
			L.`lact_no`,
			L.`lact_days`,
			L.`milk`,
			L.`fat`,
			L.`protein`,
			L.`milk_day`,
			L.`have_graph`,

			A.`time_added`,
			Di.`source`,
			A.`status`,
			Di.`special`

			FROM `animals` A

				LEFT JOIN `farms` H ON A.`id_farm`=H.`id`
				LEFT JOIN `animals` M ON A.`id_mother`=M.`id`
				LEFT JOIN `animals` F ON A.`id_father`=F.`id`

				LEFT JOIN `goats_data` Di ON A.`id`=Di.`id_goat`
				LEFT JOIN `goats_test` T ON A.`id`=T.`id_goat`
				LEFT JOIN `goats_lact` L ON L.`id`=Di.`id_lact_show`
				LEFT JOIN `goats_data` Dm ON M.`id`=Dm.`id_goat`
				LEFT JOIN `goats_data` Df ON F.`id`=Df.`id_goat`
				LEFT JOIN `breeds` B ON Di.`id_breed`=B.`id`

			%s ORDER BY A.`time_added` DESC";
		ispr($sql, $APN);
		// vd($sql);
		$DbRes = DB::run($sql);
		//vd($DbRes);
		if ( ! we($DbRes) ) return nodata();
		#`
		$Tbl = cols::shsel($DbRes, 'horns_type');
		$Tbl = cols::shsel($Tbl, 'status');
		col::c2v($Tbl, 'horns_type', u(' ,К,О,Р'));
		// col::c2v($Tbl, 'have_brand', u('Нет,Да'));
		col::c2v($Tbl, 'test_type', u('Не проведена,Классическая углубленная,Аттест. молодняка'));
		col::c2v($Tbl, 'have_graph', u('Нет,Да'));
		// col::c2v($Tbl, 'm_have_brand', u('Нет,Да'));
		// col::c2v($Tbl, 'f_have_brand', u('Нет,Да'));
		col::c2v($Tbl, 'is_abg', u('Нет,Да'));
		col::c2v($Tbl, 'have_gen', u('Нет,Да'));
		col::c2v($Tbl, 'status', u('No info,<span style="color:red;">Dead</span>,<span style="color:green;">Alive</span>'));
		col::c2v($Tbl, 'sex', u('Ж,М'));
		foreach (col($Tbl, 'id') as $xy => $val)
			col::io($Tbl, $xy, 'reg_code'
				, id2reg($val, col::io($Tbl, $xy, 'is_reg')));
		foreach (col($Tbl, 'id_f') as $xy => $val)
			if ( inat($val) ) col::io($Tbl, $xy, 'f_reg_code', $val + 10000);
		foreach (col($Tbl, 'id_m') as $xy => $val)
			if ( inat($val) ) col::io($Tbl, $xy, 'm_reg_code', $val + 10000);
		// foreach (col($Tbl, 'id_m') as $xy => $val)
		// 	col::io($Tbl, $xy, 'm_reg_code'
		// 		, id2reg($val, col::io($Tbl, $xy, 'm_reg_code')));
		$Tbl = cols::today2($Tbl, 'date_born');
		$Tbl = cols::today2($Tbl, 'time_added');
		col::c2l($Tbl, 'm_name', Route::make('catalog/goats', 'view', idk)
			, 'id_m');
 		col::c2l($Tbl, 'f_name', Route::make('catalog/goats', 'view', idk)
			, 'id_f');
		foreach ( col($Tbl, 'id_farm') as $xy => $val) {
			if ( ! zero(col::io($Tbl, $xy, 'is_reg')) ) {
				if ( ! zero($val) ) {
					col::io($Tbl, $xy, 'farm_name',
						sp(col::io($Tbl, $xy, 'farm_name'))
						. wear('F'. spr(lpq(3), $val), '['));
				} else col::io($Tbl, $xy, 'farm_name', 'Без фермы');
				$frm = col::io($Tbl, $xy, 'farm_name');
				col::io($Tbl, $xy, 'farm_name'
					, atag($frm, Route::make('farms'
							, col::io($Tbl, $xy, 'id_farm'))));
			} else col::io($Tbl, $xy, 'farm_name', '-');
		}
		// col::c2l($Tbl, 'farm_name', Route::make('farms', idk), 'id_farm');
		avaname($Tbl);
		$Tbl = col::del($Tbl, u('ava,id,id_farm,id_m,id_f,is_reg'));
		array_unshift(
			$Tbl, arc( ak(reset($Tbl)), u('Кличка,Порода,Пол,Член ABG,Ферма,Заводчик,Владелец,Дата рождения,Вес при рождении,Родился в количестве,Рогатость,Ген. паспорт,Ген. материал,Код по реестру,ID UA,ID ABG,ID по ФХ,ID Chip,ID International,Клеймо,Серия,Номер,Кличка,Код по реестру,ID UA,ID ABG,ID по ФХ,ID Chip,ID International,Клеймо,Кличка,Код по реестру,ID UA,ID ABG,ID по ФХ,ID Chip,ID International,Клеймо,Тип аттестации,Итоговый балл,Высота в холке,Высота в крестце,Обхват груди по линии сердца,Косая длина корпуса,Класс,Категория,Потомок/предок,Номер лактации,Дней лактации,Удой за лактацию в кг,Жир &percnt;,Белок &percnt;,Среднесуточный удой (кг),График лактационной кривой,Дата записи в реестр,Источник информации,Статус,Особые отметки')));
		$u0 = xarr('name, ;cert_serial,Сертификат;m_name,Данные по матери;f_name,Данные по отцу;test_type,Аттестация (избранное);viewer,Продуктивность собственная/по потомству/предкам (избранное);time_added, ');
		$Tbl = col::del($Tbl, $DEL);
		tbl::map(grid::auto($Tbl, $u0));
		return tbl::html(xarr('class,table_com'));
	}



	public function table_main2($APN) {
		$res = str;
		# Кто сейчас на ферме
		$sql = "SELECT
			Di.`ava`,
			A.`id` as id,
			A.`name` as name,
			A.`is_reg`,

			B.`alias` as breed,
			A.`sex`,

			Di.`is_abg`,
			Di.`manuf`,
			Di.`owner`,
			Di.`date_born`

			FROM `animals` A

				LEFT JOIN `goats_data` Di ON A.`id`=Di.`id_goat`
				LEFT JOIN `breeds` B ON Di.`id_breed`=B.`id`

			%s ORDER BY A.`time_added` DESC";
		ispr($sql, $APN);
		$DbRes = DB::run($sql);
		#`
		if ( we($DbRes) ) {
			$Tbl = cols::today2($DbRes, 'date_born');
			col::c2v($Tbl, 'sex', u('Ж,М'));
			col::c2v($Tbl, 'is_abg', u('Нет,Да'));
			foreach (col($Tbl, 'id') as $xy => $val)
				col::io($Tbl, $xy, 'reg_code'
					, id2reg($val, col::io($Tbl, $xy, 'is_reg')));
			avaname($Tbl);
			$Tbl = col::del($Tbl, u('ava,id,is_reg,reg_code'));
			tbl::map(grid::auto($Tbl, yes), u('Кличка,Порода,Пол,Член ABG,Заводчик,Владелец,Дата рождения'));
			$res.= tbl::html(xarr('class,table_com'));
		} else $res.= nodata(). br(2);
		$res.= '<h4 style="text-align: center;">Список перемещенных:</h4>';
		# Кто покинул ферму и не вернулся
		$farm  = Route::pull(0);
		$sql = "SELECT DISTINCT
			A.`id` as id
			FROM `animals` A
			LEFT JOIN `goats_move` M ON A.`id`=M.`id_goat`
			WHERE M.`id_farm_of`='${farm}' AND M.`id_farm_on`!='${farm}'";
		$DbRes = DB::run($sql);	
		if ( we($DbRes) ) {
			$lst_id = ax();
			foreach ( $DbRes as $Data ) $lst_id[] = q($Data['id']);
			$apn2 = wear(implode(',', $lst_id), '(');
			$sql = "SELECT
				Di.`ava`,
				A.`id` as id,
				A.`name` as name,
				A.`is_reg`,

				B.`alias` as breed,
				A.`sex`,

				Di.`is_abg`,
				Di.`manuf`,
				Di.`owner`,
				Di.`date_born`

				FROM `animals` A

					LEFT JOIN `goats_data` Di ON A.`id`=Di.`id_goat`
					LEFT JOIN `breeds` B ON Di.`id_breed`=B.`id`

				WHERE A.`id` IN %s
				ORDER BY A.`time_added` DESC";
			ispr($sql, $apn2);
			$DbRes = DB::run($sql);
			$Tbl = cols::today2($DbRes, 'date_born');
			col::c2v($Tbl, 'sex', u('Ж,М'));
			col::c2v($Tbl, 'is_abg', u('Нет,Да'));
			foreach (col($Tbl, 'id') as $xy => $val)
				col::io($Tbl, $xy, 'reg_code'
					, id2reg($val, col::io($Tbl, $xy, 'is_reg')));
			avaname($Tbl);
			$Tbl = col::del($Tbl, u('ava,id,is_reg,reg_code'));
			tbl::map(grid::auto($Tbl, yes), u('Кличка,Порода,Пол,Член ABG,Заводчик,Владелец,Дата рождения'));
			$res.= tbl::html(xarr('class,table_com'));
		} else $res.= nodata();
		return $res;
	}
}

return $self_name;
