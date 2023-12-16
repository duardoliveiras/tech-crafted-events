 
 <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                
                @if($report == "event")
                    <h5 class="modal-title" id="reportModalLabel">Report {{ $event->name }} </h5> 
                @else
                    <h5 class="modal-title" id="reportModalLabel">Report {{ $comment->user->name }} </h5> 
                @endif
                    
            </div>
            <div class="modal-body">
                @if($report == "event")
                    <form method="POST" enctype="multipart/form-data" action="{{ route('event-report.store', [$event->id]) }}">
                @else
                    <form method="POST" enctype="multipart/form-data">
                @endif
                
                @csrf
                <div class="form-group">
                    <label for="reportReason"> Reason </label>
                    <select class="form-control" id="reportReason" name="reportReason">
                        <option value="Inappropriate content">Inappropriate content</option>
                        <option value="Incorrect Information">Incorrect Information</option>
                        <option value="Inappropriate Behavior at the Event">Inappropriate Behavior at the Event</option>
                        <option value="Safety Conditions">Safety Conditions</option>
                        <option value="Fraud or Suspicious Activity">Fraud or Suspicious Activity</option>
                        <option value="Spam or Repetitive Content">Spam or Repetitive Content</option>
                        <option value="Others">Others</option>
                    </select>
                    <label for="reportDescription"> Description </label>
                        <textarea name="reportDescription" class="form-control" id="reportDescription" placeholder="Provide more details"></textarea>

                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>

            </div>
        </div>
        </div>
        