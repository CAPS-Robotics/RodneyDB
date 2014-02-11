<?php

require( "../../config.php" );
require( "../../modules/class.Core.php" );
require( "../../modules/class.MySQL.php" );

// Act like the webserver index for now, we just need the MySQL object
error_reporting( E_ALL ^ E_NOTICE );
$core = new Core( new MySQL( MYSQL_HOST, MYSQL_PORT, MYSQL_DB, MYSQL_USER, MYSQL_PASSWORD ) );

$db = $core->getDB();

// BEGIN PARSING
require( "simple_html_dom.php" );

$html = file_get_html( "Student.htm" );
$table = $html->find( 'div[id=tables1]', 1 )->find( 'table', 0 );
$numcolored = 0;

foreach( $table->find( 'tr' ) as $element ){
    if( $numcolored >= 2 ){
        $stuArray[] = $element->find( 'td', 0 )->plaintext;
        $parArray[] = $element->find( 'td', 1 )->plaintext;
    }

    if( $element->bgcolor == '#c0c0c0' )
        $numcolored += 1;
}

echo "<html><head><title>Upload results</title></head><body>";

for( $i = 0; $i < count( $stuArray ); ++$i ){
    list( $name, $email, $phone ) = explode( "\n", $stuArray[ $i ] );
    $name = trim( $name );
    $email = trim( $email );
    $phone = cleanPhone( $phone );

    if( strlen( trim( $parArray[ $i ] ) ) > 0 ){
        list( $pname, $pemail, $pphone ) = explode( "\n", $parArray[ $i ] );
        $pname = trim( $pname );
        $pemail = trim( $pemail );
        $pphone = cleanPhone( $pphone );
    
        if( ! $db->checkUserExists( $name ) ){
            $db->addNewUser( $name, $email, $phone, $pname, $pemail, $pphone );
            echo "Added new student: ". $name ."<br>";
        }
    } else {
        if( ! $db->checkUserExists( $name ) ){
            $db->addNewUser( $name, $email, $phone, null, "", "" );
            echo "Added new student: ". $name ."<br>";
        }
    }
}

echo "<br><a href=\"?p=directory\">Go back!</a></body></html>";

function cleanPhone( $phone ){
    return str_replace( str_split( "()- " ), "", trim( $phone ) );
}

?>