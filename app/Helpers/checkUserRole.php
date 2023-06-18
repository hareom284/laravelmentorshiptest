<?php


if(!function_exists("checkUserRole"))
{
    /**
     *  this function check current userrole
     *  is match or not if
     *  @param $role
     *  @return bool
     *  @hareom284
     */
    function checkUserRole($role)
    {
        if (auth()->user()->roles()->first()->name == $role) {
            return true;
        }
        else
        {
            return false;
        }
    }
}


