@extends('layouts.app')

@section('title', 'Help / FAQ')

@section('content')
    @section('breadcrumbs')
        <li> &nbsp; / Help - FAQ </li>
    @endsection
    <div class="container py-5">
        <h2 class="text-center mb-4">Help & Frequently Asked Questions</h2>
        <div class="accordion accordion-flush" id="faqAccordion">
            <!-- FAQ Item 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        How do I sign up for a new account?
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">To sign up for a new account, click the 'Sign Up' button on the top
                        right of the page, and fill in your details in the form provided.
                    </div>
                </div>
            </div>
            <!-- FAQ Item 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        How can I contact customer support?
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Our customer support team is available 24/7. Click on the 'Contact Us'
                        link at the bottom of the page for contact information.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFive" aria-expanded="false"
                            aria-controls="flush-collapseFive">
                        Is there a mobile app available?
                    </button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">Unfortunately not, we are working on our app.</div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseFour" aria-expanded="false"
                            aria-controls="flush-collapseFour">
                        How do I change my account settings?
                    </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">You can change your account settings by navigating to your account
                        dashboard and selecting 'Profile' and then selecting 'Edit profile'.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree" aria-expanded="false"
                            aria-controls="flush-collapseThree">
                        What payment methods are accepted?
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree"
                     data-bs-parent="#faqAccordion">
                    <div class="accordion-body">We accept only PayPal.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
