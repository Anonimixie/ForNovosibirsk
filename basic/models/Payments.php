<?php


namespace app\models;

use app\models\User;
use Yii;
use yii\base\Exception;

/**
 * Этот класс реализует перечисление денежных средств
 *
 * Class Payments
 * @package app\models
 */
class Payments extends \yii\base\Model {

    /**
     * Переводит деньги с одного счета на другой
     *
     * @param int $iUserIdFrom ID пользователя, который перечисляет
     * @param int $iUserIdTo Бенефициар (тот кто получает)
     * @param string $sAmount Сумма перечисления
     * @return bool Если перечисление прошло нормально, то возвратит true, иначе false
     */
    public function transfer($iUserIdFrom, $iUserIdTo, $sAmount) {
        if ($iUserIdFrom === $iUserIdTo) {
            throw new Exception('Вы пытаетесь перевести деньги одному и тому же пользователю');
        }

        $oUserModel = new User;
        $sUser1Balance  = $oUserModel->balance($iUserIdFrom);
        $sUser2Balance = $oUserModel->balance($iUserIdTo);

        if (!preg_match('~^\\d+(?:\\.\\d{1,2})?$~', $sAmount)) {
            throw new \Exception('Неверно указана сумма перевода');
        }

        if ($sUser1Balance === false || $sUser2Balance === false) {
            throw new \Exception('Пользователь с таким ID не найден');
        }

        // проверяем баланс
        // Для сравнения используем bcmath, можно также использовать gmp
        if (bccomp($sUser1Balance, $sAmount, 2) === -1) {
            throw new \Exception('Недостаточно средств на балансе');
        }

        // Делаем рассчет баланса
        $sUser1NewBalance = bcsub($sUser1Balance, $sAmount, 2); // у первого юзера отнимаем баланс
        $sUser2NewBalance = bcadd($sUser2Balance, $sAmount, 2); // второму добавляем

        // Начинаем транзакцию
        $transation = Yii::$app->db->beginTransaction();

        try {
            // фиксируем баланс 1 пользователя
            $iAffectedRows = Yii::$app->db->createCommand()->update('users', [
                'balance' => $sUser1NewBalance
            ], 'id=:id', [':id'=>$iUserIdFrom])->execute();

            // Проверяем затронутые строки, если не затронута строка
            // Если строк не затронуто, или затронуто больше чем надо, то начинаем быковать.
            if ($iAffectedRows !== 1) {
                throw new Exception('Произошла ошибка, при перечислении');
            }

            // фиксируем 2 пользователя
            $iAffectedRows = Yii::$app->db->createCommand()->update('users', [
                'balance' => $sUser2NewBalance
            ], 'id=:id', [':id'=>$iUserIdTo])->execute();

            if ($iAffectedRows !== 1) {
                throw new Exception('Произошла ошибка, при перечислении');
            }

            $transation->commit();
        } catch (\Exception $e) {
            $transation->rollBack();
            throw $e;
        }
    }
}


?>