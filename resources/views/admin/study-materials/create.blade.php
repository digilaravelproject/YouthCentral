@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add Study Material</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.study-materials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-control-label">Title</label>
                                    <input class="form-control" type="text" id="title" name="title" required value="{{ old('title') }}">
                                    @error('title') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-control-label">Material Type</label>
                                    <select class="form-control" id="type" name="type" required onchange="toggleInputs()">
                                        <option value="PDF" {{ old('type') == 'PDF' ? 'selected' : '' }}>PDF</option>
                                        <option value="Worksheet" {{ old('type') == 'Worksheet' ? 'selected' : '' }}>Worksheet</option>
                                        <option value="STEM" {{ old('type') == 'STEM' ? 'selected' : '' }}>STEM Resources (YouTube Video)</option>
                                    </select>
                                    @error('type') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="subject" class="form-control-label">Subject</label>
                                    <input class="form-control" type="text" id="subject" name="subject" value="{{ old('subject') }}">
                                    @error('subject') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="month" class="form-control-label">Month</label>
                                    <input class="form-control" type="month" id="month" name="month" value="{{ old('month') }}">
                                    @error('month') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="topic" class="form-control-label">Topic</label>
                                    <input class="form-control" type="text" id="topic" name="topic" value="{{ old('topic') }}">
                                    @error('topic') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row" id="file-input-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="file" class="form-control-label">Upload File (PDF, Worksheets)</label>
                                    <input class="form-control" type="file" id="file" name="file">
                                    @error('file') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row d-none" id="video-input-row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="video_link" class="form-control-label">YouTube Video Link</label>
                                    <input class="form-control" type="url" id="video_link" name="video_link" value="{{ old('video_link') }}">
                                    @error('video_link') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="horizontal dark mt-4">
                        <h6 class="text-uppercase text-body text-xs font-weight-bolder">Assigned Students</h6>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="student_class" class="form-control-label">Select Class</label>
                                    <select class="form-control" id="student_class" onchange="loadStudents()">
                                        <option value="">-- Choose Class --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class }}">{{ $class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div id="selected-students" class="mb-3"></div>
                                <div id="hidden-selected-inputs"></div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div id="students-container" class="border p-3 rounded d-none" style="max-height: 300px; overflow-y: auto;">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="select-all">
                                        <label class="form-check-label font-weight-bold" for="select-all">Select All Students</label>
                                    </div>
                                    <div id="students-list" class="row">
                                        <!-- Students will be loaded here via AJAX -->
                                    </div>
                                </div>
                                <div id="no-students-msg" class="text-secondary text-center py-4 d-none">
                                    No students found in this class.
                                </div>
                                <div id="loading-spinner" class="text-center py-4 d-none">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                @error('student_ids') <p class="text-danger text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.study-materials.index') }}" class="btn btn-light m-0 me-2">Cancel</a>
                            <button type="submit" class="btn bg-gradient-primary m-0">Save Material</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleInputs() {
        const type = document.getElementById('type').value;
        const fileRow = document.getElementById('file-input-row');
        const videoRow = document.getElementById('video-input-row');

        if (type === 'STEM') {
            fileRow.classList.add('d-none');
            videoRow.classList.remove('d-none');
        } else {
            fileRow.classList.remove('d-none');
            videoRow.classList.add('d-none');
        }
    }

    const selectedStudents = {};

    function updateHiddenInputs() {
        const container = document.getElementById('hidden-selected-inputs');
        container.innerHTML = '';
        Object.keys(selectedStudents).forEach(id => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'student_ids[]';
            inp.value = id;
            container.appendChild(inp);
        });
    }

    function seedAssigned(assigned) {
        if (!assigned || !assigned.length) return;
        assigned.forEach(s => {
            selectedStudents[s.id] = {
                id: s.id,
                label: `${s.first_name} ${s.last_name} (${s.mobile})`,
                grade: s.grade || ''
            };
        });
        updateHiddenInputs();
        renderSelectedChips();
    }

    async function loadStudents() {
        const studentClass = document.getElementById('student_class').value;
        const container = document.getElementById('students-container');
        const list = document.getElementById('students-list');
        const noStudentsMsg = document.getElementById('no-students-msg');
        const spinner = document.getElementById('loading-spinner');

        if (!studentClass) {
            container.classList.add('d-none');
            noStudentsMsg.classList.add('d-none');
            return;
        }

        spinner.classList.remove('d-none');
        container.classList.add('d-none');
        noStudentsMsg.classList.add('d-none');
        list.innerHTML = '';

        try {
            const response = await fetch(`{{ route('admin.study-materials.get-students') }}?class=${studentClass}`);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.error || 'Network response was not ok');
            }

            const students = await response.json();

            spinner.classList.add('d-none');

            if (students.length > 0) {
                container.classList.remove('d-none');
                students.forEach(student => {
                    const div = document.createElement('div');
                    div.className = 'col-md-4 mb-2';
                    const checked = selectedStudents[student.id] ? 'checked' : '';
                    div.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input student-checkbox" type="checkbox" name="student_ids[]" value="${student.id}" id="student_${student.id}" data-grade="${student.grade}" ${checked}>
                            <label class="form-check-label" for="student_${student.id}">
                                ${student.first_name} ${student.last_name} (${student.mobile})
                            </label>
                        </div>
                    `;
                    list.appendChild(div);
                });
                updateHiddenInputs();
                renderSelectedChips();
            } else {
                noStudentsMsg.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Error loading students:', error);
            spinner.classList.add('d-none');
            alert('Failed to load students: ' + error.message);
        }
    }

    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = this.checked;
            const id = cb.value;
            const label = cb.nextElementSibling ? cb.nextElementSibling.innerText : id;
            const grade = cb.getAttribute('data-grade') || '';
            if (this.checked) selectedStudents[id] = { id, label, grade };
            else delete selectedStudents[id];
        });
        updateHiddenInputs();
        renderSelectedChips();
    });

    // Render selected student chips
    function renderSelectedChips() {
        const container = document.getElementById('selected-students');
        if (!container) return;
        container.innerHTML = '';
        Object.values(selectedStudents).forEach(s => {
            const span = document.createElement('span');
            span.className = 'badge bg-primary text-white me-2 mb-2';
            span.innerHTML = s.label + ' <a href="#" class="text-white ms-2 remove-selected" data-id="' + s.id + '">×</a>';
            container.appendChild(span);
        });
    }

    // Delegate checkbox change to update chips
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList && e.target.classList.contains('student-checkbox')) {
            const cb = e.target;
            const id = cb.value;
            const label = cb.nextElementSibling ? cb.nextElementSibling.innerText : id;
            const grade = cb.getAttribute('data-grade') || '';
            if (cb.checked) selectedStudents[id] = { id, label, grade };
            else delete selectedStudents[id];
            updateHiddenInputs();
            renderSelectedChips();
        }
    });

    // Delegate remove click
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList && e.target.classList.contains('remove-selected')) {
            e.preventDefault();
            const id = e.target.getAttribute('data-id');
            delete selectedStudents[id];
            const cb = document.getElementById('student_' + id);
            if (cb) cb.checked = false;
            updateHiddenInputs();
            renderSelectedChips();
        }
    });

    // seed assigned if any
    const assignedStudents = @json($assignedStudents ?? []);
    seedAssigned(assignedStudents);

    // Initial toggle
    toggleInputs();
</script>
@endpush
@endsection
