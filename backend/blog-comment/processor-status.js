let comment = document.getElementsByClassName("display");
for (let index = 0; index < comment.length; index++) {
  comment[index].addEventListener("change", function () {
    let commentId = comment[index].getAttribute("data-id");
    let commentToken = comment[index].getAttribute("data-token");
    let commentStatus = comment[index].getAttribute("value");

    const data = {
      display: commentStatus,
      id: commentId,
      token: commentToken,
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
