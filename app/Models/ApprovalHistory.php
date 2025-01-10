<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ApprovalHistory Model
 *
 * 管理者が行った承認履歴を管理するモデル。
 * 多態的関連を利用し、異なる申請モデル（例: 月報申請や休暇申請）に対応しています。
 *
 * @package App\Models
 */
class ApprovalHistory extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可する属性。
     *
     * @var array
     */
    protected $fillable = [
        'application_id',    // 申請のID
        'application_type',  // 申請の種類（多態的関連のため）
        'admin_id',          // 管理者のID
        'action',            // 承認アクション（approved, rejected など）
        'comment',           // 承認または却下に付加するコメント
    ];

    /**
     * 承認履歴を作成した管理者を取得します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *     管理者モデルとのリレーションを返します。
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * 多態的関連を定義します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     *     関連付けられた申請モデル（例: MonthlyReport, LeaveRequest）を返します。
     */
    public function application()
    {
        // 多態的関連のための定義
        return $this->morphTo();
    }
}
