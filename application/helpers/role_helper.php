<?php
if (!function_exists('getRoles')) {
    function getRoles()
    {
//        $roles=array("System Admin","Department Admin");
        $ci = &get_instance();
        $ci->load->library('User_role');
        return $ci->user_role->getRoleList();
    }
}
if (!function_exists('getRoleNameById')) {
    function getRoleNameById($id)
    {
        $roleList = getRoles();
        foreach ($roleList as $key => $val) {
            if ($val['id'] == $id) {
                return $val['display_name'];
            }
        }
        return null;
    }
}
if (!function_exists('checkRoleById')) {
    function checkRoleById($roleKey, $id)
    {
        $roleList = getRoles();
        foreach ($roleList as $key => $val) {
            echo '<pre>';
            print_r($val['id']);
            die();
            if ($key == $roleKey && $val['id'] == $id) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('getRoleIdByKey')) {
    function getRoleIdByKey($roleKey)
    {
        $roleList = getRoles();
        if (gettype($roleKey) == 'array') {
            $roleIdArray = [];
            foreach ($roleKey as $roleKy) {
                foreach ($roleList as $key => $val) {
                    if ($key == $roleKy) {
                        $roleIdArray[] = strval($val['id']);
                    }
                }
            }
            return $roleIdArray;
        } else {
            foreach ($roleList as $key => $val) {
                if ($key == $roleKey) {
                    return strval($val['id']);
                }
            }
        }
        return null;
    }
}

if (!function_exists('getRoleKeyById')) {
    function getRoleKeyById($id)
    {
        $ci = &get_instance();
//        $role=$ci->session->userdata('role');
//        $val='';
        $ci->load->model('roles_model');
        $role = $ci->roles_model->get_by_doc_id($id);
        return $role->slug;
//        switch ($role->{'_id'}->{'$id'}) {
//            case '5f1abe880873000087002831':
//                $val='DPS';
//                break;
//            case '5f1abe940873000087002834':
//                $val='APPELLATE_AUTH';
//                break;
//            case '5f1abe9e0873000087002837':
//                $val='REVIEWING_AUTH';
//                break;
//            case '5f48fbdb8a1f9841030d0fa2':
//                $val='PFC';
//                break;
//            default:
//                $val='SYSTEM_ADMIN';
//                break;
//        }
//        return $val;
    }
}

if (!function_exists('getRoleName')) {
    function getRoleName($roleID)
    {
        $ci = &get_instance();
        $ci->load->database();
        $ci->db->select("*");

        $ci->db->where("roleId", $roleID);
        $ci->db->from("tbl_roles");
        $query = $ci->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->role;
        }
    }
}
