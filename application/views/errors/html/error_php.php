<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="ci-php-error-card" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #ffffff; border: 1px solid #fed7aa; border-left: 5px solid #f97316; border-radius: 12px; box-shadow: 0 6px 16px rgba(249, 115, 22, 0.08); margin: 20px 0; overflow: hidden; text-align: left; line-height: 1.5; color: #1e293b;">
    <div style="background: #fff7ed; padding: 12px 18px; border-bottom: 1px solid #ffedd5; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <svg style="width: 20px; height: 20px; color: #ea580c; flex-shrink: 0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <span style="font-weight: 700; font-size: 14.5px; color: #9a3412;">PHP Error Encountered</span>
        </div>
        <span style="background: #ea580c; color: #ffffff; padding: 2px 10px; border-radius: 9999px; font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
            Severity: <?= htmlspecialchars($severity) ?>
        </span>
    </div>

    <div style="padding: 16px 20px; font-size: 13.5px;">
        <div style="margin-bottom: 10px;">
            <span style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Pesan Error</span>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; color: #0f172a; font-weight: 600; word-break: break-word;">
                <?= htmlspecialchars($message) ?>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-top: 12px;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block;">File</span>
                <span style="font-family: Consolas, Monaco, monospace; font-size: 12.5px; color: #0369a1; word-break: break-all;"><?= htmlspecialchars($filepath) ?></span>
            </div>
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 12px;">
                <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block;">Line Number</span>
                <span style="font-family: Consolas, Monaco, monospace; font-size: 13px; font-weight: 700; color: #ea580c;"><?= htmlspecialchars($line) ?></span>
            </div>
        </div>

        <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>
            <div style="margin-top: 16px;">
                <details style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden;">
                    <summary style="padding: 10px 14px; font-size: 12.5px; font-weight: 600; color: #475569; cursor: pointer; user-select: none; background: #f1f5f9;">
                        Lihat Debug Backtrace (Klik untuk Membuka)
                    </summary>
                    <div style="padding: 12px 14px; font-family: Consolas, Monaco, monospace; font-size: 12px; color: #334155; max-height: 250px; overflow-y: auto;">
                        <?php $bt_count = 0; ?>
                        <?php foreach (debug_backtrace() as $error): ?>
                            <?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
                                <?php $bt_count++; ?>
                                <div style="padding: 6px 8px; border-bottom: 1px dashed #cbd5e1; display: flex; flex-direction: column; gap: 2px;">
                                    <div><strong style="color: #64748b;">#<?= $bt_count ?></strong> <span style="color: #0284c7;"><?= htmlspecialchars($error['file']) ?></span>:<strong style="color: #ea580c;"><?= htmlspecialchars($error['line']) ?></strong></div>
                                    <div style="color: #475569; padding-left: 18px;">&rarr; Function: <span style="color: #7c3aed;"><?= htmlspecialchars($error['function']) ?>()</span></div>
                                </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </details>
            </div>
        <?php endif ?>
    </div>
</div>