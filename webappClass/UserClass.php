<?php

class UserClass
{
    function userLogin($conn,$loginid,$password)
    {
        $query  = "select * from employee where bh_emailid = '$loginid' and bh_user_password = '$password' and bh_status = '103'";
        $result = mysqli_query($conn, $query);
        $count  = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response = array("status"=>"Success",
                                    "id"=>$row["id"],
                                    "bh_id"=>$row["bh_id"],
                                    "name"=>$row["bh_name"],
                                    "username"=>$row["bh_user_name"],
                                    "mobileno"=>$row["bh_mobileno"],
                                    "emailid"=>$row["bh_emailid"],
                                    "preloaddata"=>$this->getPreLoadData($conn,$row["user_role"]));
            }
        }
        else
        {
            $response = array("status"=>"Failure","message"=>"Login Failure");
        }
        return $response;
    }
    
    function getPreLoadData($conn,$roleid)
    {
        $response = array("responsibilities"=>$this->getResponsibility($conn, $roleid),
                          "menus"=>$this->getMenu($conn, $roleid));
        return $response;
    }
    
    function getResponsibility($conn,$roleid)
    {
        $query = "select resp_id from role_resp_mapping where role_id = '$roleid'";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("id"=>$row["resp_id"]);
            }
        }
        else
        {
            $response[] = array();
        }
        
        return $response;
    }
    
    function getMenu($conn,$roleid)
    {
        $query = "select m.name as menu_name,m.url as menu_url,m.icon as menu_icon from menu m join menu_role_mapping mrp on m.id = mrp.menu_id where mrp.role_id = '$roleid'";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("name"=>$row["menu_name"],
                                    "url"=>$row["menu_url"],
                                    "icon"=>$row["menu_icon"]);
            }
        }
        else
        {
            $response[] = array();
        }
        
        return $response;
    }
    
    function getAllUserDetails($conn)
    {
        $query  = "select emp.*,status.code_description,r.name as role_name from employee emp join statuscode status on status.code_id = emp.bh_status join roles r on r.id = emp.user_role";
        $result = mysqli_query($conn,$query);
        $count = mysqli_num_rows($result);
        
        if($count > 0)
        {
            while($row = mysqli_fetch_array($result))
            {
                $response[] = array("status"=>"Success","id"=>$row["id"],"bh_id"=>$row["bh_id"],"bh_name"=>$row["bh_name"],
                                    "bh_user_name"=>$row["bh_user_name"],"bh_mobileno"=>$row["bh_mobileno"],
                                    "bh_emailid"=>$row["bh_emailid"],"code_description"=>$row["code_description"],"role_name"=>$row["role_name"]);
            }
        }
        else
        {
            $response[] = array("status"=>"Failure");
        }
        return $response;
    }
}
