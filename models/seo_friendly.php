<?php

# this script set unique friendly url for articles

# where we get all bike company with empty friendly url
try {
    $results = $db->prepare("SELECT * FROM company WHERE friendly_url = '' ");
    $results->execute();
  } catch(Exception $e) {
    echo $e->getMessage();
    die();
  }

# for each article with empty friendly_url 
while($row = PDO::mysql_fetch_assoc($results)){
    
    # get friendly url from name
    $new_friendly_url = $vl_url_string = friendly_seo_string($row['name']);
    
    $counter = 1;
    $intial_friendly_url = $new_friendly_url;
    
    # while we not found unique friendly url
    while($results->rowCount($db->prepare("SELECT * 
                                           FROM company 
                                           WHERE friendly_url = '$new_friendly_url' ")))
    {
                                                   
        # if friendly url is already used by other article 
        # -> increase counter until we find free url
        $counter++;  
        
        # we reapeat this until url-2 url-3 url-4..... until we find not used url for articles
        $new_friendly_url = "{$intial_friendly_url}-{$counter}";
        } # END while
    
        
    # once we found friendly url we set it to articles table, 
    # then we can  use it to build our frinedly url
    mysqli_query($link, "UPDATE articles
                         SET friendly_url = '{$new_friendly_url}' 
                         WHERE id = '{$row['id']}' ");
    
    } # END while


/**
 * function, receives string, returns seo friendly version for that strings, 
 *     sample: 'Hotels in Buenos Aires' => 'hotels-in-buenos-aires'
 *    - converts all alpha chars to lowercase
 *    - converts any char that is not digit, letter or - into - symbols into "-"
 *    - not allow two "-" chars continued, converte them into only one syngle "-"
 */
function friendly_seo_string($vp_string){
    
    $vp_string = trim($vp_string);
    
    $vp_string = html_entity_decode($vp_string);
    
    $vp_string = strip_tags($vp_string);
    
    $vp_string = strtolower($vp_string);
    
    $vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
    
    $vp_string = preg_replace('~ ~', '-', $vp_string);
    
    $vp_string = preg_replace('~-+~', '-', $vp_string);
        
    return $vp_string;
    } # friendly_seo_string()

?>