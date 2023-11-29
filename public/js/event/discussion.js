document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.upvote-btn, .downvote-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            let commentId = this.getAttribute('data-comment-id');
            let voteType = this.getAttribute('data-vote-type');

            fetch(`/comments/${commentId}/toggle-vote/${voteType}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then(response => response.json())
                .then(data => {
                    let newVoteType = data.newVoteType;
                    let voteCount = data.voteCount;

                    let upvoteButton = document.querySelector(`.upvote-btn[data-comment-id="${commentId}"]`);
                    if (newVoteType === 1) {
                        upvoteButton.innerHTML = '<svg id="upvote-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512"><style>#upvote-filled{fill:#49a835}</style><path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM135.1 217.4l107.1-99.9c3.8-3.5 8.7-5.5 13.8-5.5s10.1 2 13.8 5.5l107.1 99.9c4.5 4.2 7.1 10.1 7.1 16.3c0 12.3-10 22.3-22.3 22.3H304v96c0 17.7-14.3 32-32 32H240c-17.7 0-32-14.3-32-32V256H150.3C138 256 128 246 128 233.7c0-6.2 2.6-12.1 7.1-16.3z"/></svg>';
                    } else {
                        upvoteButton.innerHTML = '<svg id="upvote-not-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512"><style>#upvote-not-filled{fill:#d3d7cf}</style><path d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM135.1 217.4c-4.5 4.2-7.1 10.1-7.1 16.3c0 12.3 10 22.3 22.3 22.3H208v96c0 17.7 14.3 32 32 32h32c17.7 0 32-14.3 32-32V256h57.7c12.3 0 22.3-10 22.3-22.3c0-6.2-2.6-12.1-7.1-16.3L269.8 117.5c-3.8-3.5-8.7-5.5-13.8-5.5s-10.1 2-13.8 5.5L135.1 217.4z"/></svg>';
                    }

                    let downvoteButton = document.querySelector(`.downvote-btn[data-comment-id="${commentId}"]`);
                    if (newVoteType === -1) {
                        downvoteButton.innerHTML = '<svg id="downvote-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512"><style>#downvote-filled{fill:#ef2929}</style><path d="M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6L269.8 394.5c-3.8 3.5-8.7 5.5-13.8 5.5s-10.1-2-13.8-5.5L135.1 294.6c-4.5-4.2-7.1-10.1-7.1-16.3c0-12.3 10-22.3 22.3-22.3l57.7 0 0-96c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 96 57.7 0c12.3 0 22.3 10 22.3 22.3c0 6.2-2.6 12.1-7.1 16.3z"/></svg>';
                    } else {
                        downvoteButton.innerHTML = '<svg id="downvote-not-filled" xmlns="http://www.w3.org/2000/svg" height="2em" viewBox="0 0 512 512"><style>#downvote-not-filled{fill:#d3d7cf}</style><path d="M256 464a208 208 0 1 1 0-416 208 208 0 1 1 0 416zM256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6c4.5-4.2 7.1-10.1 7.1-16.3c0-12.3-10-22.3-22.3-22.3H304V160c0-17.7-14.3-32-32-32l-32 0c-17.7 0-32 14.3-32 32v96H150.3C138 256 128 266 128 278.3c0 6.2 2.6 12.1 7.1 16.3l107.1 99.9c3.8 3.5 8.7 5.5 13.8 5.5s10.1-2 13.8-5.5l107.1-99.9z"/></svg>';
                    }

                    let voteCountElement = document.getElementById(`vote-count-${commentId}`);
                    voteCountElement.innerText = voteCount;
                })
                .catch(error => {
                    console.error('Erro ao processar a solicitação:', error);
                });
        });
    });
});