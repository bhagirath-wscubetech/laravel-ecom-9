<?php

/**
 * Function to print the array in a proper format
 * @param array
 * @return null
 */
if (!function_exists("p")) {
    function p($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

/**
 * To generate a random name for the file
 * @param $fileName
 * @return string
 */
if (!function_exists("getRandomFileName")) {
    function getRandomFileName($fileName)
    {
        return md5(time() . rand(1, 10000)) . $fileName;
    }
}

/**
 * To set the current menu and sub menu name
 * @param $menu and $subMenu
 * @return void
 */
if (!function_exists('setMenuStatus')) {
    function setMenuStatus($menu, $subMenu)
    {
        session(["menu" => $menu, "sub-menu" => $subMenu]);
        return;
    }
}
/**
 * To check the menu name
 * @param $menu 
 * @return boolean
 */
if (!function_exists('checkMenuName')) {
    function checkMenuName($menu)
    {
        return session()->get('menu') == $menu ? true : false;
    }
}

/**
 * To check the sub menu name
 * @param $subMenu 
 * @return boolean
 */
if (!function_exists('checkSubMenuName')) {
    function checkSubMenuName($subMenu)
    {
        return session()->get('sub-menu') == $subMenu ? true : false;
    }
}

// if (!function_exists('uploadFile')) {
//     function uploadFile($file, $dest)
//     {
//         $success = true;
//         try {
//             $fileName = getRandomFileName($file->getClientOriginalName());
//             $filePath = public_path($dest);
//             $file->move($filePath, $fileName);
//         } catch (Exception $err) {
//             $success = false;
//         }
//         return $success;
//     }
// }
