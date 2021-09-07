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
    public function CreateNewUser($inviterId, $ownerId, $newUserId, $headUserId = 0){
        $Basic = new BasicController();
        $Logs = new LogsController();

        $Basic->insertFee($inviterId);
        $Basic->insertRollUp($inviterId);

        $Logs->insertKey($ownerId, $newUserId);
        if ($headUserId > 0) $Logs->insertCouple($headUserId);

        $this->saveLevelState($newUserId);
    }

    /**
     * คำนวนค่าครบคู่สำหรับ Cronjob
     *
     * @param integer $headUserId   UserID ที่จะให้ระบบเริ่มคำนวนค่าครบคู่ (default: 1, เริ่มคำนวนตั้งแต่ต้นสายใหม่ทั้งหมด)
     *
     * @author Aom (siriwat576@gmail.com)
     */
    public function CalculateCouple($headUserId = 1){
        $Logs = new LogsController();
        $Logs->insertCouple($headUserId);
    }

    /**
     * คำนวนค่าครบคู่สำหรับ Cronjob
     *
     * @param integer $upgradedUser         UserID ที่ต้องการอัพเกรด
     *
     * @author Aom (siriwat576@gmail.com)
     */
    public function UpgradeUser($upgradedUser){
        $Basic = new BasicController();

        $this->saveLevelState($upgradedUser);
        $Basic->upgradeUser($upgradedUser);
    }

    /**
     * คำนวนค่าครบคู่ใหม่ตั้งแต่ $headUserId (UserID)
     *
     * @param integer $headUserId   UserID ที่จะให้ระบบเริ่มคำนวนค่าครบคู่ (default: 1, เริ่มคำนวนตั้งแต่ต้นสายใหม่ทั้งหมด)
     *
     * @author Aom (siriwat576@gmail.com)
     */
    public function RecalculateCouple($headUserId = 1){
        $Logs = new LogsController();
        $Logs->insertCouple($headUserId);
    }

    /**
     * ทดสอบระบบสร้าง Transactions ของการคำนวน MLM
     *
     * @param integer $Id       UserID ที่จะให้ระบบเริ่มคำนวนค่าครบคู่
     * @param integer $childId  UserID ที่สมัครใหม่
     *
     * @author Aom (siriwat576@gmail.com)
     */
    public function TestCreateAll($Id, $childId){
        return $this->CreateNewUser($Id, $Id, $childId, $Id);
    }
}
?>
