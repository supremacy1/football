

<?php $__env->startSection('title', 'Live Match Fixtures'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; color: #1c1e21; margin: 20px; }
    h2 { color: #3d195d; text-align: center; margin-bottom: 25px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .container { max-width: 900px; margin: auto; background: white; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.08); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th { background-color: #3d195d; color: white; padding: 15px; font-size: 0.75rem; text-transform: uppercase; text-align: center; }
    td { padding: 16px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    tr:hover { background-color: #f8f9fa; }
    
    .team-row { display: flex; align-items: center; gap: 12px; font-weight: 600; font-size: 0.95rem; }
    .team-logo { width: 28px; height: 28px; object-fit: contain; }
    .text-right { text-align: right; justify-content: flex-end; }
    
    .score-box { background: #222; color: #fff; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-family: 'Courier New', monospace; display: inline-block; min-width: 50px; text-align: center; font-size: 1.1rem; }
    .vs { color: #888; font-weight: 400; font-size: 0.8rem; }

    .status-badge { font-size: 0.7rem; padding: 4px 10px; border-radius: 20px; background: #eaedef; color: #4b4f56; font-weight: bold; display: inline-block; }
    .status-live { background: #e91e63; color: white; animation: blinker 1.5s cubic-bezier(.5, 0, 1, 1) infinite alternate; }
    
    @keyframes blinker { from { opacity: 1; } to { opacity: 0.4; } }
    
    .match-time { font-size: 0.75rem; color: #65676b; display: block; margin-top: 4px; }
    .round-info { font-size: 0.7rem; color: #bcc0c4; text-transform: uppercase; font-weight: bold; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h2 class="mt-4">Live Matches / Fixture</h2>
    <div class="container">
    <?php if($error): ?>
        <div class="alert alert-danger" role="alert" style="padding: 40px; text-align: center;">
            <strong>Error:</strong> <?php echo e($error); ?>

            <?php if(isset($rawData) && $rawData): ?>
                <pre class="text-start mt-3"><?php echo e(json_encode($rawData, JSON_PRETTY_PRINT)); ?></pre>
            <?php endif; ?>
        </div>
    <?php elseif(empty($matches)): ?>
        <p style="padding: 40px; text-align: center; color: #666;">No matches scheduled for today.</p>
        <?php if(isset($rawData) && $rawData): ?>
            <div style="background: #eee; padding: 10px; border: 1px solid #ccc;">
                <strong>Debug Information (Raw API Response):</strong>
                <pre><?php echo e(json_encode($rawData, JSON_PRETTY_PRINT)); ?></pre>
            </div>
        <?php endif; ?>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th width="15%">Time</th>
                <th width="30%" class="text-right">Home</th>
                <th width="10%">Score</th>
                <th width="30%">Away</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                    $statusShort = $match['fixture']['status']['short'] ?? 'N/A';
                    $isLive = in_array($statusShort, ['1H', '2H', 'HT', 'ET', 'P', 'BT', 'LIVE']);
                    $homeTeam = $match['teams']['home'] ?? ['name' => 'Unknown', 'logo' => ''];
                    $awayTeam = $match['teams']['away'] ?? ['name' => 'Unknown', 'logo' => ''];
                    $homeScore = $match['goals']['home'] ?? null;
                    $awayScore = $match['goals']['away'] ?? null;
                    $matchTime = isset($match['fixture']['timestamp']) ? \Carbon\Carbon::createFromTimestamp($match['fixture']['timestamp'])->format('H:i') : '--:--';
                    $roundInfo = str_replace('Regular Season - ', 'R', $match['league']['round'] ?? '');
                ?>
                <tr>
                    <td style="text-align: center;">
                        <span class="round-info"><?php echo e($roundInfo); ?></span>
                        <span class="match-time"><?php echo e($matchTime); ?></span>
                    </td>
                    <td>
                        <div class="team-row text-right">
                            <span><?php echo e($homeTeam['name']); ?></span>
                            <img class="team-logo" src="<?php echo e($homeTeam['logo']); ?>" alt="logo">
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <div class="score-box">
                        <?php if($homeScore !== null && $awayScore !== null): ?>
                            <?php echo e($homeScore); ?> - <?php echo e($awayScore); ?>

                        <?php else: ?>
                            <span class="vs">VS</span>
                        <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div class="team-row">
                            <img class="team-logo" src="<?php echo e($awayTeam['logo']); ?>" alt="logo">
                            <span><?php echo e($awayTeam['name']); ?></span>
                        </div>
                    </td>
                    <td style="text-align: center;">
                        <span class="status-badge <?php echo e($isLive ? 'status-live' : ''); ?>">
                            <?php if($isLive && isset($match['fixture']['status']['elapsed'])): ?>
                                <?php echo e($match['fixture']['status']['elapsed']); ?>'
                            <?php else: ?>
                                <?php echo e($statusShort); ?>

                            <?php endif; ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/live.blade.php ENDPATH**/ ?>