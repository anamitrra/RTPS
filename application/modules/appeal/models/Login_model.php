<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
class Login_model extends Mongo_model {

  public $table = 'users';
  public $id = 'userId';
  public $order = 'DESC';
  /**
   * This function used to check the login credentials of the user
   * @param string $email : This is email of the user
   * @param string $password : This is encrypted password of the user
   */
  // public function loginMe($email, $password) {
  //   $select = array("_id", "name","username","photo", "mobile", "email", "password", "roleId", "department","is_verified");
  //   $this->mongo_db->select($select);
  //   $this->mongo_db->where('email', $email);
  //   $user = $this->mongo_db->find_one($this->table);
  //   if (!empty($user)) {
  //     if (verifyHashedPassword($password, $user->password)) {
  //       return $user;
  //     } else {
  //       return array();
  //     }
  //   } else {
  //     return array();
  //   }
  // }
  public function loginMe($email, $password) {
    // echo($email.$password);
    // return;
    $select = array("_id", "name", "username","designation", "photo", "mobile", "email", "password", "roleId", "department", "is_verified");
    $this->mongo_db->select($select);
    $this->mongo_db->where('email', $email);
    $users = $this->mongo_db->where(array())->get($this->table); // Retrieve all documents
 

    foreach ($users as $user) {
        if (verifyHashedPassword($password, $user->password)) {
            return $user;
        }
    }

    return array(); // No matching user found
}

// loginUsingUsername
public function loginUsingUsername($username, $password) {
  // echo($username.$password);
  // return;

  $select = array("_id", "name", "username","designation", "photo", "mobile", "email", "password", "roleId", "department", "is_verified");
  $this->mongo_db->select($select);
  $this->mongo_db->where('username', $username);
  $users = $this->mongo_db->where(array())->get($this->table); // Retrieve all documents
  // print_r($users);
  // return;

  foreach ($users as $user) {
      if (verifyHashedPassword($password, $user->password)) {
          return $user;
      }
  }

  return array(); // No matching user found
}



//   public function loginMe($email = null, $password = null, $username = null) {
//     $select = array("_id", "name", "username", "photo", "mobile", "email", "password", "roleId", "department", "is_verified");
//     $this->mongo_db->select($select);

//     if ($email !== null) {
//         $this->mongo_db->where('email', $email);
//     } elseif ($username !== null) {
//         $this->mongo_db->where('username', $username);
//     }

//     $user = $this->mongo_db->find_one($this->table);
//     if (!empty($user)) {
//         if (verifyHashedPassword($password, $user->password)) {
//             return $user;
//         } else {
//             return array();
//         }
//     } else {
//         return array();
//     }
// }

    // Select office user from list of users having same email
    public function selectUser($id) {
    $select = array("_id", "name","username","photo", "mobile", "email", "password", "roleId", "department","is_verified");

    // die($id);
    $this->mongo_db->select($select);
    $this->mongo_db->where('_id', new ObjectId($id));
    $user = $this->mongo_db->find_one($this->table);

    // return $user;
    if (!empty($user)) {
        return $user;
    } else {
      return array();
    }
    } 

    public function selectUserByEmail($email) {
      $select = array("_id", "name","username","photo", "mobile", "email", "password", "roleId", "department","is_verified");
  
      // die($id);
      $this->mongo_db->select($select);
      $this->mongo_db->where('email', $email);
      $user = $this->mongo_db->find_one($this->table);
  
      // return $user;
      if (!empty($user)) {
          return $user;
      } else {
        return array();
      }
      } 
  

  public function getDepartment($deptID) {
    if ($deptID != null) {
      //echo $deptID;
      $this->mongo_db->where("department_id", (string) ($deptID));
      $result = $this->mongo_db->get("departments");
      return $result->{'0'};
    }
  }

  /**
   * This function used to check email exists or not
   * @param {string} $email : This is users email id
   * @return {boolean} $result : TRUE/FALSE
   */

  public function checkEmailExist($email) {
    // $this->db->select('userId');
    // $this->db->where('email', $email);
    // $this->db->where('isDeleted', 0);
    // $query = $this->db->get('tbl_users');

    $count = $this->mongo_db->mongo_like_count(array('email' => $email), 'users');

    if ($count > 0) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * This function used to insert reset password data
   * @param {array} $data : This is reset password data
   * @return {boolean} $result : TRUE/FALSE
   */

  public function resetPasswordUser($data) {
    // $result = $this->db->insert('tbl_reset_password', $data);

    if ($this->mongo_db->insert('reset_password', $data)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * This function is used to get customer information by email-id for forget password email
   * @param string $email : Email id of customer
   * @return object $result : Information of customer
   */

  public function getCustomerInfoByEmail($email) {
    $select = array("userId", "name", "email");
    $this->mongo_db->select($select);
    $this->mongo_db->where('email', $email);
    $this->mongo_db->where('isDeleted', '0');

    return $this->mongo_db->find_one('users');
  }

  /**
   * This function used to check correct activation deatails for forget password.
   * @param string $email : Email id of user
   * @param string $activation_id : This is activation string
   */
  public function checkActivationDetails($email, $activation_id) {
    $this->mongo_db->where('email', $email);
    $this->mongo_db->where('activation_id', $activation_id);
    $this->mongo_db->where('is_expired',false);
    return $this->mongo_db->find_one('reset_password');
  }

  // This function used to create new password by reset link
  public function createPasswordUser($email, $password) {
    $this->db->where('email', $email);
    $this->db->where('isDeleted', 0);
    $this->db->update('tbl_users', array('password' => getHashedPassword($password)));
    $this->db->delete('tbl_reset_password', array('email' => $email));
  }
}
