<?php
namespace App\Http\Controllers\MLM;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * คำนวน Point สำหรับลงข้อมูล User ใหม่
     *
     * @param integer $inviterId    UserID ของคนที่ชวน
     * @param integer $ownerId      UserID ของคนที่กรอก
     * @param integer $newUserId    UserID ของคนที่สมัครใหม่
     * @param integer $headUserId   UserID ที่จะให้ระบบเริ่มคำนวนค่าครบคู่ (default: 1, เริ่มคำนวนตั้งแต่ต้นสายใหม่ทั้งหมด)
     *
     * @author Aom (siriwat576@gmail.com)
     */
    public function CreateNewUser($inviterId, $ownerId, $newUserId, $headUserId = 1){
        $Basic = new BasicController();
        $Logs = new LogsController();

        $Basic->insertFee($inviterId);
        $Basic->insertRollUp($inviterId);

        $Logs->insertKey($ownerId, $newUserId);
        $Logs->insertCouple($headUserId);
    }

    /**
     * คำนวน Point สำหรับลงข้อมูล User ใหม่
     *
     * @param integer $headUserId   UserID ที่จะให้ระบบเริ่มคำนวนค่าครบคู่ (default: 1, เริ่มคำนวนตั้งแต่ต้นสายใหม่ทั้งหมด)
     *
     * @author Aom (siriwat576@gmail.com)
     */
    public function RecalcuateCouple($headUserId = 1){
        $Logs = new LogsController();
        $Logs->insertCouple($headUserId);
    }
}
?>
