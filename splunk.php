<?php
$id = $_POST["id"];
include 'report_config.php';


//$filename = "data.csv";

 
//  header('Content-Type: text/csv; charset=utf-8');

//  header('Content-Disposition: attachment; filename='.$filename);
//  $output = fopen("php://output","w");
// fputcsv($output, array('Name','Manufacture','Alternative Username','Serial Number','Network','Domain','UUID','OS NAme','Location','MAC','Firmware'));
//  $date_today = date("Y-m-d");
//  $date_today_plus=date("Y-m-d",strtotime('+30 days',strtotime(date("Y-m-d"))));
//  $date_month = date("Y-m-d",strtotime('-1 month',strtotime(date("Y-m-d"))));
  $query = "SELECT `glpi_computers`.`name`,`glpi_computers`.`id`,`glpi_computers`.`serial`,`glpi_computers`.`contact`,`glpi_computers`.`comment`,`glpi_computers`.`date_mod`,`glpi_computers`.`uuid`,`glpi_domains`.`name` AS domain,`glpi_computers`.`date_creation`,`glpi_manufacturers`.`name` AS manufact,`glpi_computermodels`.`name` AS model,`glpi_computertypes`.`name` AS type ,`glpi_operatingsystems`.`name` as OS 
FROM `glpi_computers`
INNER JOIN `glpi_manufacturers`
ON `glpi_computers`.`manufacturers_id` = `glpi_manufacturers`.`id`
INNER JOIN `glpi_computermodels`
ON `glpi_computers`.`computermodels_id` = `glpi_computermodels`.`id`
INNER JOIN `glpi_computertypes`
ON `glpi_computers`.`computertypes_id` = `glpi_computertypes`.`id`
INNER JOIN `glpi_domains`
ON `glpi_computers`.`domains_id` = `glpi_domains`.`id`
INNER JOIN `glpi_items_operatingsystems`
ON `glpi_items_operatingsystems`.`items_id` = `glpi_computers`.`id`
INNER JOIN `glpi_operatingsystems`
ON `glpi_operatingsystems`.`id` = `glpi_items_operatingsystems`.`operatingsystems_id`
WHERE `glpi_computers`.`is_deleted` = 0 order by `glpi_computers`.`id` ASC ";
//INNER JOIN `glpi_items_devicenetworkcards`
//ON  `glpi_items_devicenetworkcards`.`items_id` = `glpi_computers`.`id`  and not `glpi_items_devicenetworkcards`.`mac` = '00:00:00:00:00:00'
//INNER JOIN `glpi_items_devicefirmwares`
//ON `glpi_items_devicefirmwares`.`items_id` = `glpi_computers`.`id` and `glpi_items_devicefirmwares`.`itemtype` = 'Computer'
//INNER JOIN `glpi_devicefirmwares`
//ON `glpi_items_devicefirmwares`.`devicefirmwares_id` = `glpi_devicefirmwares`.`id`limit 50";


//, `glpi_items_devicenetworkcards`.`mac`,`glpi_devicefirmwares`.`designation` AS firmware 
$result = $conn->query($query);

if ($result->num_rows > 0) {
//    $v=0;
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $compname[$row["id"]] = $row["name"];
        $compmanu[$row["id"]] = $row["manufact"];
        $compuser[$row["id"]] = $row["contact"];
        $compserial[$row["id"]] = $row["serial"];
        $compnetwork[$row["id"]] = "";
        $compdomain[$row["id"]] = $row["domain"];
        $compuuid[$row["id"]] = $row["uuid"];
        $composname[$row["id"]] = $row["OS"];
        $comploc[$row["id"]] = "";
//        echo $row["id"].": " . $row["name"]. " - Serial : " . $row["serial"]. "- Domain : " . $row["domain"]. "<br>";
        $v = $row["id"];
    }
           for($c=0; $c<= $v; $c++)
               
        { 
               if (!empty ($compname[$c]))
              {
                   
//            echo $c."-".$compid[$c]."<br>";
               $sql = "SELECT `glpi_items_devicenetworkcards`.`mac` FROM `glpi_items_devicenetworkcards` WHERE `glpi_items_devicenetworkcards`.`items_id` = ".$c." AND NOT `glpi_items_devicenetworkcards`.`mac` = '00:00:00:00:00:00' LIMIT 4";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $a=0;
//    $b='a';
    // output data of each row
    while($row = $result->fetch_assoc()) {
//          echo $a;
//  echo $c."&nbsp;".$row["mac"] ."<br>";
  ${"MAC".$a}[$c] = $row["mac"];
        $a++;
//        $b++;
      
    }
    
    
} else {
//    echo $conn->error;
}
                   $sql = "SELECT `glpi_devicefirmwares`.`designation`
FROM `glpi_items_devicefirmwares`
inner join `glpi_devicefirmwares`
on `glpi_devicefirmwares`.`id` = `glpi_items_devicefirmwares`.`devicefirmwares_id`
WHERE `glpi_items_devicefirmwares`.`items_id` = ".$c." and `glpi_items_devicefirmwares`.`itemtype` = 'Computer'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
//        echo $c."&nbsp;".$row["designation"] ."<br>";
        $fw[$c] = $row["designation"];
    }
} else {
//    echo "0 results";
}
//                   
  $sql = "SELECT `glpi_ipaddresses`.`name` as ip
FROM `glpi_ipaddresses`
where `glpi_ipaddresses`.`mainitems_id`='".$c."'
limit 4";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $a=0;
//    $b='a';
    // output data of each row
    while($row = $result->fetch_assoc()) {
//          echo $a;
//  echo $c."&nbsp;".$row["ip"] ."<br>";
          ${"IP".$a}[$c] = $row["ip"];
//  $ip[$a] = $row["ip"];
        $a++;
//        $b++;
      
    }
               }
    else
    {
        
    }
               }
                   
               
        }
//$filename = "splunk_data.csv";
//
// 
//  header('Content-Type: text/csv; charset=utf-8');
//
//  header('Content-Disposition: attachment; filename='.$filename);
//  $output = fopen("php://output","w");
    
// fputcsv($output, array('Name','Manufacture','Alternative Username','Serial Number','Network','Domain','UUID','OS NAme','Location','MAC 1','MAC 2','MAC 3','MAC 4','Firmware'));
 
    $data .= "Name,Manufacture,Alternative Username,Serial Number,Network IP1,Network IP2,Network IP3,Network IP4,Domain,UUID,OS NAme,Location,MAC 1,MAC 2,MAC 3,MAC 4,Firmware \n";
    for ($r=0; $r<= $v; $r++)
        
{   if (!empty($compname[$r]))
       {
//        fputcsv($output, array($compname[$r],$compmanu[$r],$compuser[$r],$compserial[$r],$IP0[$r].",".$IP1[$r].",".$IP2[$r].",".$IP3[$r],$compdomain[$r],$compuuid[$r],$composname[$r],$comploc[$r],$MAC0[$r],$MAC1[$r],$MAC2[$r],$MAC3[$r],$fw[$r]));
     $data .= $compname[$r].",".$compmanu[$r].",".$compuser[$r].",".$compserial[$r].",".$IP0[$r].",".$IP1[$r].",".$IP2[$r].",".$IP3[$r].",".$compdomain[$r].",".$compuuid[$r].",".$composname[$r].",".$comploc[$r].",".$MAC0[$r].",".$MAC1[$r].",".$MAC2[$r].",".$MAC3[$r].",".$fw[$r]."\n";

}
    }
    
//    fclose($output);
$csv_handler = fopen ('splunk_data.csv','w');
fwrite ($csv_handler,$data);
fclose ($csv_handler);
} else {
//    echo "0 results";
}
$conn->close();
?>