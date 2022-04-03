let post = document.getElementsByClassName("display");
for (let index = 0; index < post.length; index++) {
  post[index].addEventListener("change", function () {
    let postId = post[index].getAttribute("data-id");
    let postToken = post[index].getAttribute("data-token");
    let postStatus = post[index].getAttribute("value");
    console.log(postId, postStatus, postToken);

    const data = {
      display: postStatus,
      id: postId,
      token: postToken,
    };

    fetch("processor-status.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      credentials: "same-origin",
      body: JSON.stringify(data),
    })
      .then((response) => response.json())
      .then((data) => {
        let result = JSON.parse(data);
        console.log("Success:", result);
        if ((result.status = "success")) {
          this.checked = "on";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });
}
