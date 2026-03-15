@extends('layouts.dashboard')

@section('title', 'Vote Now')

@section('page-title', 'Vote Now')
@section('page-subtitle', 'Cast your votes carefully. Once submitted, votes cannot be changed.')

@section('content')
    <div class="mx-auto flex max-w-6xl flex-col gap-6">
        {{-- Election selector (if multiple elections) --}}
        @if ($activeElections->count() > 1)
            <section
                class="rounded-2xl bg-gradient-to-r from-sky-600 to-sky-500 px-6 py-5 text-white shadow-sm ring-1 ring-sky-500/40">
                <h2 class="mb-3 text-sm font-semibold uppercase tracking-wide text-sky-100">
                    📋 Select an election to vote in
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach ($activeElections as $elec)
                        <a href="{{ route('voter.vote', ['election_id' => $elec->id]) }}"
                            class="inline-flex items-center rounded-full border px-4 py-2 text-sm font-medium transition
                            {{ $elec->id === $election->id ? 'border-white bg-white text-sky-700 shadow-sm' : 'border-white/60 bg-white/10 text-sky-50 hover:bg-white/20' }}">
                            {{ $elec->title }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Voting header --}}
        <header
            class="rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 px-6 py-6 text-white shadow-md ring-1 ring-emerald-500/40">
            <h1 class="flex items-center gap-2 text-xl font-semibold md:text-2xl">
                <span class="text-2xl">🗳️</span>
                <span>{{ $election->title }}</span>
            </h1>
            <p class="mt-2 max-w-2xl text-sm md:text-base text-emerald-50">
                Cast your votes carefully. Once submitted, votes cannot be changed.
            </p>
        </header>

        {{-- Validation alert --}}
        <div id="validationAlert"
            class="hidden items-start gap-2 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <span class="text-lg" aria-hidden="true">⚠️</span>
            <p id="validationMessage" class="leading-relaxed"></p>
        </div>

        {{-- Voting progress --}}
        <section class="rounded-2xl bg-white px-5 py-4 shadow-sm ring-1 ring-slate-200 md:px-6 md:py-5"
            aria-labelledby="voting-progress-title">
            <h2 id="voting-progress-title" class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                Your voting progress
            </h2>
            <div class="mb-4 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                <div id="overallProgress"
                    class="h-full w-0 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400 transition-[width] duration-300 ease-out">
                </div>
            </div>
            <dl class="grid gap-3 text-center text-xs md:grid-cols-3 md:text-sm">
                <div class="rounded-lg bg-slate-50 px-3 py-2">
                    <dt class="text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500">
                        Positions completed
                    </dt>
                    <dd id="completedCount" class="mt-1 text-lg font-semibold text-emerald-600">0</dd>
                </div>
                <div class="rounded-lg bg-slate-50 px-3 py-2">
                    <dt class="text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500">
                        Total positions
                    </dt>
                    <dd id="totalPositions" class="mt-1 text-lg font-semibold text-slate-900">
                        {{ $election->positions->count() }}
                    </dd>
                </div>
                <div class="rounded-lg bg-slate-50 px-3 py-2">
                    <dt class="text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500">
                        Votes selected
                    </dt>
                    <dd id="totalVotes" class="mt-1 text-lg font-semibold text-emerald-600">0</dd>
                </div>
            </dl>
        </section>

        {{-- Voting form --}}
        <form method="POST" action="{{ route('voter.submit') }}" id="voteForm" class="space-y-6">
            @csrf
            <input type="hidden" name="election_id" value="{{ $election->id }}">

            <div class="grid gap-5 md:grid-cols-2">
                @foreach ($election->positions as $position)
                    <section aria-label="{{ $position->name }}"
                        class="position-card flex flex-col rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200 transition hover:shadow-md"
                        data-position-id="{{ $position->id }}" data-max="{{ $position->max_votes ?? 1 }}">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-base font-semibold text-emerald-700">
                                {{ $position->name }}
                            </h3>
                            <span
                                class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                Select up to {{ $position->max_votes ?? 1 }}
                            </span>
                        </div>

                        <div
                            class="position-status mt-3 inline-flex items-center gap-2 rounded-md bg-slate-50 px-3 py-2 text-xs font-medium text-slate-600">
                            <span id="status-{{ $position->id }}" class="inline-block h-2 w-2 rounded-full bg-amber-400"
                                aria-hidden="true"></span>
                            <span id="status-text-{{ $position->id }}">
                                0 / {{ $position->max_votes ?? 1 }} selected
                            </span>
                        </div>

                        <div class="mt-4 flex flex-col gap-2">
                            @foreach ($position->candidates as $candidate)
                                <label
                                    class="candidate-card group relative flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm transition hover:-translate-y-0.5 hover:border-emerald-400 hover:bg-emerald-50/50 hover:shadow-sm">
                                    <input type="checkbox" name="votes[{{ $position->id }}][]"
                                        value="{{ $candidate->id }}"
                                        class="candidate-checkbox mt-1 h-4 w-4 shrink-0 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 position-{{ $position->id }}-checkbox"
                                        data-position-id="{{ $position->id }}"
                                        aria-label="Select {{ $candidate->name }} for {{ $position->name }}">

                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-emerald-400 text-sm font-semibold text-white shadow-sm">
                                        {{ mb_substr($candidate->name, 0, 1, 'UTF-8') }}
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-2">
                                            <p class="font-semibold text-slate-900">
                                                {{ $candidate->name }}
                                            </p>
                                            <span
                                                class="hidden rounded-full bg-emerald-100 px-2 py-0.5 text-[0.65rem] font-semibold text-emerald-700 group-[.is-selected]:inline-flex">
                                                Selected
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs leading-snug text-slate-600">
                                            Candidate for {{ $position->name }}.
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            {{-- Sticky actions --}}
            <div
                class="form-actions sticky bottom-4 z-10 flex flex-col gap-3 rounded-2xl bg-white/95 p-4 shadow-lg ring-1 ring-slate-200 backdrop-blur">
                <div class="flex flex-col gap-3 md:flex-row">
                    <a href="{{ route('voter.dashboard') }}"
                        class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                        ← Back to dashboard
                    </a>
                    <button type="submit" id="submitBtn"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 px-4 py-3 text-sm font-semibold text-white shadow-md transition disabled:cursor-not-allowed disabled:opacity-60 hover:from-emerald-500 hover:to-emerald-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500"
                        disabled>
                        <span id="submitBtnLabel">✓ Submit your vote</span>
                        <span id="submitBtnSpinner"
                            class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"
                            aria-hidden="true"></span>
                    </button>
                </div>
                <p class="text-center text-[0.7rem] text-slate-500">
                    Once submitted, your selections are final and cannot be changed.
                </p>
            </div>
        </form>
    </div>

    {{-- Confirmation dialog --}}
    <div id="confirmModal"
        class="fixed inset-0 z-20 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-sm">
        <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-xl">
            <h2 class="text-base font-semibold text-slate-900">
                Confirm your vote
            </h2>
            <p class="mt-2 text-sm text-slate-600">
                Please review your selections carefully. Once you submit, you will not be able to change your vote.
            </p>
            <div class="mt-4 flex flex-col gap-2 rounded-lg bg-slate-50 p-3 text-xs text-slate-600">
                <p>• All positions must have exactly the allowed number of candidates selected.</p>
                <p>• Your choices are stored securely and anonymously.</p>
            </div>
            <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:justify-end">
                <button type="button" id="cancelConfirmBtn"
                    class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Review again
                </button>
                <button type="button" id="confirmSubmitBtn"
                    class="inline-flex flex-1 items-center justify-center rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:from-emerald-500 hover:to-emerald-400">
                    Yes, submit my vote
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const form = document.getElementById('voteForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnLabel = document.getElementById('submitBtnLabel');
            const submitBtnSpinner = document.getElementById('submitBtnSpinner');
            const validationAlert = document.getElementById('validationAlert');
            const validationMessage = document.getElementById('validationMessage');
            const totalPositions = {{ $election->positions->count() }};
            const confirmModal = document.getElementById('confirmModal');
            const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
            const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
            let isConfirmedSubmission = false;

            // Update progress display
            function updateProgress() {
                let completedCount = 0;
                let totalVotesSelected = 0;

                document.querySelectorAll('.position-card').forEach(card => {
                    const positionId = card.getAttribute('data-position-id');
                    const maxVotes = parseInt(card.getAttribute('data-max'));
                    const checkedBoxes = document.querySelectorAll(`.position-${positionId}-checkbox:checked`);
                    const checkedCount = checkedBoxes.length;

                    // Update position status
                    const statusIndicator = document.getElementById(`status-${positionId}`);
                    const statusText = document.getElementById(`status-text-${positionId}`);

                    statusText.textContent = `${checkedCount} / ${maxVotes} selected`;

                    if (checkedCount === maxVotes) {
                        statusIndicator.classList.remove('bg-amber-400');
                        statusIndicator.classList.add('bg-emerald-500');
                        card.classList.add('ring-2', 'ring-emerald-400', 'bg-emerald-50/60');
                        completedCount++;
                    } else {
                        statusIndicator.classList.remove('bg-emerald-500');
                        statusIndicator.classList.add('bg-amber-400');
                        card.classList.remove('ring-2', 'ring-emerald-400', 'bg-emerald-50/60');
                    }

                    totalVotesSelected += checkedCount;
                });

                // Update progress bar
                const percentage = (completedCount / totalPositions) * 100;
                document.getElementById('overallProgress').style.width = percentage + '%';
                document.getElementById('completedCount').textContent = completedCount;
                document.getElementById('totalVotes').textContent = totalVotesSelected;

                // Enable submit button only if all positions are completed
                submitBtn.disabled = completedCount < totalPositions;
            }

            // Add change listeners to all checkboxes
            document.querySelectorAll('.candidate-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const positionId = this.getAttribute('data-position-id');
                    const positionCard = document.querySelector(
                        `.position-card[data-position-id="${positionId}"]`);
                    const maxVotes = parseInt(positionCard.getAttribute('data-max'));
                    const checkedBoxes = document.querySelectorAll(`.position-${positionId}-checkbox:checked`);
                    const checkedCount = checkedBoxes.length;

                    // Prevent selecting more than max votes
                    if (checkedCount > maxVotes) {
                        this.checked = false;
                        showValidation(
                            `You can only select ${maxVotes} candidate(s) for ${positionCard.querySelector('.position-title').textContent.trim().split('\n')[0]}`
                        );
                    } else {
                        // Update visual selected state on candidate cards
                        document.querySelectorAll(
                                `.position-${positionId}-checkbox`)
                            .forEach(box => {
                                const card = box.closest('.candidate-card');
                                if (!card) return;

                                if (box.checked) {
                                    card.classList.add('is-selected', 'border-emerald-400',
                                        'bg-emerald-50');
                                    card.classList.remove('bg-slate-50', 'border-slate-200');
                                } else {
                                    card.classList.remove('is-selected', 'border-emerald-400',
                                        'bg-emerald-50');
                                    card.classList.add('bg-slate-50', 'border-slate-200');
                                }
                            });

                        hideValidation();
                        updateProgress();
                    }
                });
            });

            function showValidation(message) {
                validationMessage.textContent = message;
                validationAlert.classList.add('show');
                setTimeout(() => hideValidation(), 4000);
            }

            function hideValidation() {
                validationAlert.classList.remove('show');
            }

            // Form submission
            form.addEventListener('submit', function(e) {
                let valid = true;
                let errorMessages = [];

                document.querySelectorAll('.position-card').forEach(card => {
                    const positionId = card.getAttribute('data-position-id');
                    const maxVotes = parseInt(card.getAttribute('data-max'));
                    const checkedBoxes = document.querySelectorAll(`.position-${positionId}-checkbox:checked`);
                    const checkedCount = checkedBoxes.length;

                    if (checkedCount !== maxVotes) {
                        valid = false;
                        errorMessages.push(
                            `${card.querySelector('.position-title').textContent.trim().split('\n')[0]}: Select exactly ${maxVotes} candidate(s)`
                        );
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    showValidation('Please complete all positions before submitting: ' + errorMessages.join('; '));
                    return;
                }

                // If user already confirmed once, allow normal submission
                if (isConfirmedSubmission) {
                    setLoadingState(true);
                    return;
                }

                // Show custom confirmation modal instead of default confirm()
                e.preventDefault();
                openConfirmModal();
            });

            // Initial progress update
            updateProgress();

            function openConfirmModal() {
                if (!confirmModal) return;
                confirmModal.classList.remove('hidden');
                confirmModal.classList.add('flex');
            }

            function closeConfirmModal() {
                if (!confirmModal) return;
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
            }

            function setLoadingState(isLoading) {
                if (!submitBtn) return;
                if (isLoading) {
                    submitBtn.disabled = true;
                    if (submitBtnSpinner) submitBtnSpinner.classList.remove('hidden');
                    if (submitBtnLabel) submitBtnLabel.textContent = 'Submitting...';
                } else {
                    submitBtn.disabled = false;
                    if (submitBtnSpinner) submitBtnSpinner.classList.add('hidden');
                    if (submitBtnLabel) submitBtnLabel.textContent = '✓ Submit your vote';
                }
            }

            if (cancelConfirmBtn) {
                cancelConfirmBtn.addEventListener('click', () => {
                    closeConfirmModal();
                });
            }

            if (confirmSubmitBtn) {
                confirmSubmitBtn.addEventListener('click', () => {
                    isConfirmedSubmission = true;
                    closeConfirmModal();
                    setLoadingState(true);
                    form.submit();
                });
            }
        </script>
    @endpush

@endsection
