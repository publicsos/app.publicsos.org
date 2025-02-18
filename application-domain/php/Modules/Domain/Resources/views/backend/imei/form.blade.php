<div class="card">
    <div class="card-body">
        <x-forms.post-form :action="$route" class="custom-validation" novalidate>
            <div class="row">
                <div class="mb-3 col-12 col-sm-4">
                    <div class="form-group">
                        <?php
                        $field_name = 'tac';
                        $field_lable = label_case($field_name);
                        $field_placeholder = $field_lable;
                        $required = "required";
                        ?>
                        {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! field_required($required) !!}
                        {{ html()->text($field_name)
                            ->placeholder($field_placeholder)
                            ->class('form-control')
                            ->attributes(["$required", "maxlength"=>"255"])
                        }}
                    </div>
                </div>

                <div class="mb-3 col-12 col-sm-4">
                    <div class="form-group">
                        <?php
                        $field_name = 'model';
                        $field_lable = label_case($field_name);
                        $field_placeholder = $field_lable;
                        $required = "required";
                        ?>
                        {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! field_required($required) !!}
                        {{ html()->text($field_name)
                            ->placeholder($field_placeholder)
                            ->class('form-control')
                            ->attributes(["$required", "maxlength"=>"255"])
                        }}
                    </div>
                </div>

                <div class="mb-3 col-12 col-sm-4">
                    <div class="form-group">
                        <?php
                        $field_name = 'date';
                        $field_lable = label_case($field_name);
                        $field_placeholder = $field_lable;
                        $required = "required";
                        ?>
                        {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! field_required($required) !!}
                        {{ html()->date($field_name)
                            ->placeholder($field_placeholder)
                            ->class('form-control')
                            ->attributes(["$required"])
                            ->value(date('Y-m-d'))
                        }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-12 col-sm-6">
                    <div class="form-group">
                        <?php
                        $field_name = 'contributor';
                        $field_lable = label_case($field_name);
                        $field_placeholder = $field_lable;
                        $required = "required";
                        ?>
                        {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! field_required($required) !!}
                        {{ html()->text($field_name)
                            ->placeholder($field_placeholder)
                            ->class('form-control')
                            ->attributes(["$required", "maxlength"=>"255"])
                        }}
                    </div>
                </div>

                <div class="mb-3 col-12 col-sm-6">
                    <div class="form-group">
                        <?php
                        $field_name = 'status';
                        $field_lable = label_case($field_name);
                        $field_placeholder = "-- Select an option --";
                        $required = "required";
                        $select_options = [
                            '1' => 'Active',
                            '0' => 'Inactive',
                            '2' => 'Pending'
                        ];
                        ?>
                        {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! field_required($required) !!}
                        {{ html()->select($field_name, $select_options)
                            ->placeholder($field_placeholder)
                            ->class('form-control select2')
                            ->attributes(["$required"])
                        }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="mb-3 col-12">
                    <div class="form-group">
                        <?php
                        $field_name = 'comment';
                        $field_lable = label_case($field_name);
                        $field_placeholder = $field_lable;
                        $required = "";
                        ?>
                        {{ html()->label($field_lable, $field_name)->class('form-label') }} {!! field_required($required) !!}
                        {{ html()->textarea($field_name)
                            ->placeholder($field_placeholder)
                            ->class('form-control')
                            ->attributes(["$required", "rows"=>3])
                        }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {{ html()->submit($text = "Submit")->class('btn btn-primary') }}
                        <a href="{{ route('admin.phone-tacs.index') }}" class="btn btn-danger" data-toggle="tooltip" title="{{__('labels.backend.cancel')}}">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </x-forms.post-form>
    </div>
</div>

<x-library.select2 />

@push('after-scripts')
<script type="text/javascript">
$(document).ready(function() {
    $('.select2').select2({
        theme: "bootstrap",
        placeholder: "-- Select an option --"
    });

    // Enable form validation
    $('.custom-validation').parsley();
});
</script>
@endpush