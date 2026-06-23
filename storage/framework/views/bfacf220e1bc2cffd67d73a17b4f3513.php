

<?php $__env->startSection('title', 'Football Betting'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row">
        <!-- Upcoming Matches -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Betting Fixtures
                    <?php if($availableBetsCount > 0 && $firstPendingMatchId): ?>
                        <a href="#match-<?php echo e($firstPendingMatchId); ?>" class="text-decoration-none">
                            <span class="badge bg-warning text-dark ms-2" style="font-size: 0.4em; vertical-align: middle; cursor: pointer;">
                                <i class="fas fa-bell animate-pulse"></i> <?php echo e($availableBetsCount); ?> Waiting
                            </span>
                        </a>
                    <?php endif; ?>
                </h2>
                <div class="d-flex align-items-center gap-2">
                    <div class="badge bg-light text-dark px-3 py-2">Wallet Balance: ₦<?php echo e(number_format(optional(auth()->user()->wallet)->balance ?? 0, 2)); ?></div>
                    <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#withdrawModal">Withdraw Funds</button>
                </div>
            </div>

            <!-- Withdrawal History Table -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-dark text-white py-3 border-bottom border-secondary">
                    <!-- <h5 class="mb-0 fw-bold"><i class="fas fa-file-invoice-dollar me-2 text-danger"></i>Withdrawal History</h5> -->
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <!-- <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr class="small text-uppercase text-white-50">
                                    <th class="ps-3">Date</th>
                                    <th>Bank Details</th>
                                    <th>Amount</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="ps-3 small text-muted"><?php echo e($withdrawal->created_at->format('d M, Y H:i')); ?></td>
                                        <td>
                                            <div class="fw-bold small"><?php echo e($withdrawal->bank_name); ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;"><?php echo e($withdrawal->account_number); ?></div>
                                        </td>
                                        <td class="fw-bold">₦<?php echo e(number_format($withdrawal->amount, 2)); ?></td>
                                        <td class="text-center">
                                            <span class="badge <?php echo e($withdrawal->status === 'success' ? 'bg-success' : ($withdrawal->status === 'failed' ? 'bg-danger' : 'bg-warning')); ?>">
                                                <?php echo e(strtoupper($withdrawal->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">No withdrawal history recorded yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table> -->
                    </div>
                </div>
            </div>

            <?php if($matches->isEmpty()): ?>
                <div class="alert alert-info">No upcoming matches available for betting.</div>
            <?php endif; ?>

            <?php $__currentLoopData = $matches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $match): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="card mb-3 shadow-sm border-0" id="match-<?php echo e($match->id); ?>">
                    <div class="card-body">
                        <div class="row align-items-center text-center">
                            <div class="col-4">
                                <img src="<?php echo e(($match->homeClub && $match->homeClub->logo) ? (str_starts_with($match->homeClub->logo, 'http') ? $match->homeClub->logo : asset('storage/'.$match->homeClub->logo)) : 'https://via.placeholder.com/40'); ?>" class="avatar mb-2" style="width: 50px; height: 50px; object-fit: contain;">
                                <h6 class="mb-0 fw-bold"><?php echo e($match->homeClub->name); ?></h6>
                            </div>
                            <div class="col-4">
                                <div class="badge bg-light text-muted mb-1"><?php echo e($match->match_date->format('d M, H:i')); ?></div>
                                <div class="fw-bold my-1 text-primary">VS</div>
                                
                                <?php if($match->bets->count() > 0): ?>
                                    <div class="mt-2 text-start">
                                        <p class="small fw-bold mb-1 text-center">Open Bets:</p>
                                        <div class="d-flex flex-column gap-1">
                                            <?php $__currentLoopData = $match->bets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $openBet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <form action="<?php echo e(route('betting.store', $match)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="amount" value="<?php echo e($openBet->amount); ?>">
                                                    <input type="hidden" name="selection" value="<?php echo e($openBet->selection === 'home' ? 'away' : 'home'); ?>">
                                                    <button type="submit" class="btn btn-xs btn-outline-warning w-100 py-1" style="font-size: 0.7rem;">
                                                        Match ₦<?php echo e(number_format($openBet->amount)); ?> 
                                                        (<?php echo e($openBet->selection === 'home' ? $match->awayClub->name : $match->homeClub->name); ?> Win)
                                                    </button>
                                                </form>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-2">
                                        <button class="btn btn-sm btn-success px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#betModal<?php echo e($match->id); ?>">
                                            Start a Bet
                                        </button>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div class="col-4">
                                <img src="<?php echo e(($match->awayClub && $match->awayClub->logo) ? (str_starts_with($match->awayClub->logo, 'http') ? $match->awayClub->logo : asset('storage/'.$match->awayClub->logo)) : 'https://via.placeholder.com/40'); ?>" class="avatar mb-2" style="width: 50px; height: 50px; object-fit: contain;">
                                <h6 class="mb-0 fw-bold"><?php echo e($match->awayClub->name); ?></h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bet Modal -->
                <div class="modal fade" id="betModal<?php echo e($match->id); ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="<?php echo e(route('betting.store', $match)); ?>" method="POST" class="modal-content">
                            <?php echo csrf_field(); ?>
                            <div class="modal-header border-0">
                                <h5 class="modal-title fw-bold">Stake on this Match</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Your Prediction</label>
                                    <select name="selection" class="form-select" required>
                                        <option value="home"><?php echo e($match->homeClub->name); ?> to Win</option>
                                        <option value="away"><?php echo e($match->awayClub->name); ?> to Win</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Stake Amount (₦)</label>
                                    <input type="number" name="amount" class="form-control" placeholder="Minimum 100" min="100" required>
                                    <p class="text-muted mt-2 small">
                                        <i class="fas fa-info-circle"></i> Once placed, the system will look for a partner. If you match with someone already waiting, the game will be "Matched" instantly.
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Find Partner / Place Bet</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- My Bets Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 90px;">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-history"></i> My Bets Dashboard</h5>
                    </div>
                </div>
                <div class="list-group list-group-flush" style="max-height: 70vh; overflow-y: auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $myBets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="list-group-item p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge <?php echo e($bet->status === 'pending' ? 'bg-secondary' : ($bet->status === 'locked' ? 'bg-danger' : ($bet->status === 'won' ? 'bg-success' : 'bg-info'))); ?>">
                                    <?php echo e(strtoupper($bet->status)); ?>

                                </span>
                                <small class="text-muted"><?php echo e($bet->created_at->diffForHumans()); ?></small>
                            </div>
                            
                            <div class="fw-bold small mb-1"><?php echo e($bet->match->homeClub->name); ?> vs <?php echo e($bet->match->awayClub->name); ?></div>
                            <div class="small text-muted mb-2">
                                Selection: <span class="text-primary fw-bold"><?php echo e($bet->selection === 'home' ? $bet->match->homeClub->name : $bet->match->awayClub->name); ?></span>
                                <br>Amount: <strong>₦<?php echo e(number_format($bet->amount, 2)); ?></strong>
                            </div>

                            <?php if($bet->partner): ?>
                                <div class="bg-light p-2 rounded small mb-2 border">
                                    <i class="fas fa-handshake"></i> Partner: <strong><?php echo e($bet->partner->name ?? 'User'); ?></strong>
                                </div>
                            <?php endif; ?>

                            <div class="mt-2">
                                <?php if($bet->status === 'matched'): ?>
                                    <form action="<?php echo e(route('betting.confirm', $bet)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-outline-primary w-100 fw-bold">Confirm Participation</button>
                                    </form>
                                <?php elseif($bet->status === 'confirmed'): ?>
                                    <form action="<?php echo e(route('betting.lock', $bet)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-warning w-100 fw-bold">Lock & Debit Funds</button>
                                    </form>
                                <?php elseif($bet->status === 'locked' && $bet->match->status === 'finished'): ?>
                                    <form action="<?php echo e(route('betting.claim', $bet)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-success w-100 fw-bold pulse-green">Claim Result</button>
                                    </form>
                                <?php elseif($bet->status === 'pending'): ?>
                                    <button type="button" class="btn btn-sm btn-danger w-100 fw-bold" 
                                            data-bs-toggle="modal" data-bs-target="#cancelBetModal" 
                                            data-bet-id="<?php echo e($bet->id); ?>" data-bet-amount="<?php echo e(number_format($bet->amount, 2)); ?>"
                                            data-bet-match="<?php echo e($bet->match->homeClub->name); ?> vs <?php echo e($bet->match->awayClub->name); ?>">
                                        Cancel Bet
                                    </form>
                                <?php endif; ?>
                            </div>

                            <?php if($bet->status === 'won'): ?>
                                <div class="alert alert-success mt-2 py-1 small mb-0 border-0">
                                    <i class="fas fa-trophy text-warning"></i> Won ₦<?php echo e(number_format($bet->payout, 2)); ?>!
                                </div>
                            <?php elseif($bet->status === 'lost'): ?>
                                <div class="alert alert-light mt-2 py-1 small mb-0 border-0 text-muted">
                                    Better luck next time.
                                </div>
                            <?php elseif($bet->status === 'cancelled'): ?>
                                <div class="alert alert-warning mt-2 py-1 small mb-0 border-0">
                                    Unmatched. Stake refunded.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-coins fa-2x mb-2 opacity-25"></i>
                            <p class="small mb-0">You haven't placed any bets yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div> <!-- End col-lg-4 -->
    </div> <!-- End row -->
</div>
</div> <!-- End container -->

<!-- Withdrawal Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="withdrawModalLabel"><i class="fas fa-university me-2"></i>Withdraw to Bank</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('withdrawal.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-4">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Available Balance</small>
                        <h4 class="fw-bold text-dark mb-0">₦<?php echo e(number_format(optional(auth()->user()->wallet)->balance ?? 0, 2)); ?></h4>
                    </div>
                    <input type="hidden" name="account_name" id="account_name_hidden">
                    <input type="hidden" name="bank_name" id="bank_name_hidden">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Bank</label>
                        <select name="bank_code" id="bank_code" class="form-select shadow-sm" required>
                            <option value="">Choose bank...</option>
                            <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                <option value="<?php echo e($bank['code']); ?>"><?php echo e($bank['name']); ?></option> 
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Account Number</label>
                        <input type="text" name="account_number" id="account_number" class="form-control shadow-sm" maxlength="10" placeholder="e.g. 0123456789" required>
                        <div id="verify_msg" class="small mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Amount to Withdraw (₦)</label>
                        <input type="number" name="amount" class="form-control shadow-sm" placeholder="Minimum 100" min="100" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="withdrawSubmitBtn" class="btn btn-danger px-4 fw-bold" disabled>Confirm Withdrawal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Bet Confirmation Modal (Outside the grid for layout stability) -->
<div class="modal fade" id="cancelBetModal" tabindex="-1" aria-labelledby="cancelBetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="cancelBetModalLabel">Confirm Bet Cancellation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p style="color:black">Are you sure you want to cancel this bet?</p>
                <p><strong>Match:</strong> <span id="modalBetMatch"></span></p>
                <p><strong>Amount:</strong> ₦<span id="modalBetAmount"></span></p>
                <div class="alert alert-warning small mt-3">
                    <i class="fas fa-exclamation-triangle"></i> This action cannot be undone. Your stake will be refunded to your wallet.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Bet</button>
                <form id="cancelBetForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger">Yes, Cancel Bet</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .pulse-green { animation: pulse-green 1.5s infinite; }
    @keyframes pulse-green { 0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); } }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var cancelBetModal = document.getElementById('cancelBetModal');
        cancelBetModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var betId = button.getAttribute('data-bet-id');
            var betAmount = button.getAttribute('data-bet-amount');
            var betMatch = button.getAttribute('data-bet-match');

            var modalBetMatch = cancelBetModal.querySelector('#modalBetMatch');
            var modalBetAmount = cancelBetModal.querySelector('#modalBetAmount');
            var cancelBetForm = cancelBetModal.querySelector('#cancelBetForm');

            modalBetMatch.textContent = betMatch;
            modalBetAmount.textContent = betAmount;
            cancelBetForm.action = `/betting/cancel/${betId}`; // Set the form action dynamically
        });
    });

    // Withdrawal Verification Logic
    const bankSelect = document.getElementById('bank_code');
    const accountInput = document.getElementById('account_number');
    const verifyMsg = document.getElementById('verify_msg');
    const withdrawBtn = document.getElementById('withdrawSubmitBtn');

    async function verifyAccount() {
        if (bankSelect.value && accountInput.value.length === 10) {
            verifyMsg.className = 'text-primary small'; 
            verifyMsg.textContent = 'Verifying account details...';
            
            try {
                const response = await fetch("<?php echo e(route('withdrawal.verify')); ?>", {
                    method: 'POST', 
                    headers: {
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({bank_code: bankSelect.value, account_number: accountInput.value})
                });
                const data = await response.json();
                if (data.success) {
                    verifyMsg.className = 'text-success small fw-bold bg-light p-2 rounded d-block'; 
                    verifyMsg.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + data.account_name;
                    document.getElementById('account_name_hidden').value = data.account_name;
                    document.getElementById('bank_name_hidden').value = bankSelect.options[bankSelect.selectedIndex].text;
                    withdrawBtn.disabled = false;
                } else {
                    verifyMsg.className = 'text-danger small'; 
                    verifyMsg.textContent = 'Invalid account number or bank selection.';
                    withdrawBtn.disabled = true;
                }
            } catch (e) { withdrawBtn.disabled = true; }
        }
    }
    bankSelect.addEventListener('change', verifyAccount);
    accountInput.addEventListener('input', verifyAccount);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\banta\resources\views/betting/index.blade.php ENDPATH**/ ?>