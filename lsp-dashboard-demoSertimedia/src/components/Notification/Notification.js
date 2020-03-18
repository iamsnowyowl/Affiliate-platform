import React, { Component } from "react";
import { Card, CardBody } from "reactstrap";
import $ from "jquery";
import { baseUrl, path_notif, query_notif } from "../config/config";
import { Digest } from "../../containers/Helpers/digest";
import "../../css/loaderDataTable.css";
import axios from "axios";

export default class Notification extends Component {
  componentDidMount() {
    function read_Notif(notification_id) {
      const auth = Digest(path_notif + "/" + notification_id, "GET");
      const options = {
        method: auth.method,
        headers: {
          Authorization: auth.digest,
          "X-Lsp-Date": auth.date,
          "Content-Type": "application/json"
        },
        url: baseUrl + path_notif + "/" + notification_id
      };
      axios(options).then(res => {
        const jsonData = JSON.parse(res.data.data.data);

        const acc = jsonData.accessor_id;
        const schedule = jsonData.assessment_id;
        switch (jsonData.last_state_assessor) {
          case "ACCEPTED":
            alert("Assessor " + acc + " was Accepted schedule " + schedule);
            break;

          case "WAITING":
            alert(
              "Schedule " +
                schedule +
                " waiting confirmation from Assessor " +
                acc
            );
            break;

          case "DECLINED":
            alert("Assessor Declined schedule " + schedule);
            break;

          default:
            break;
        }
      });
    }

    $.DataTable = require("datatables.net-responsive-bs4");
    $(document).ready(function() {
      var table = $("#notification").DataTable({
        responsive: true,
        scrollX: true,
        ajax: {
          url: baseUrl + path_notif + query_notif,
          beforeSend: function(ark) {
            const authentication = Digest(path_notif, "GET");
            ark.setRequestHeader("Authorization", authentication.digest);
            ark.setRequestHeader("X-Lsp-Date", authentication.date);
          },
          dataFilter: function(data) {
            var json = JSON.parse(data);
            for (let index = 0; index < json.data.length; index++) {
              const element = json.data[index];
              // const data = JSON.parse(element.data);

              // set time
              const unix = new Date(element.time_stamp * 1000);
              const year = unix.getFullYear();
              const month_arr = [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec"
              ];
              const month = month_arr[unix.getMonth()];
              const day = unix.getDay();
              const hours = unix.getHours();
              const minute = "0" + unix.getMinutes();
              const seconds = "0" + unix.getSeconds();
              const time_stamp =
                day +
                " " +
                month +
                " " +
                year +
                ", " +
                hours +
                ":" +
                minute.substr(-2) +
                ":" +
                seconds.substr(-2);
              json.data[index].time_stamp = time_stamp;

              //set status
              if (element.is_read === "0") {
                json.data[index].is_read = "Unread";
              } else {
                json.data[index].is_read = "Read";
              }
            }
            json.recordsTotal = json.count;
            json.recordsFiltered = json.count;
            return JSON.stringify(json);
          },
          error: function(error) {
            console.log("error");
          },
          statusCode: {
            401: function() {
              localStorage.clear();
              window.location.replace("/login");
            }
          }
        },
        lengthChange: false,
        serverSide: true,
        processing: true,
        oLanguage: {
          sProcessing: '<div class="lds-ripple"><div></div><div></div></div>'
        },
        order: [1, "desc"],
        columnDefs: [
          {
            name: "message",
            data: "message",
            title: "Message",
            targets: 0,
            sortable: true,
            filterable: true
          },
          {
            name: "time_stamp",
            data: "time_stamp",
            title: "Time",
            targets: 1,
            sortable: true,
            filterable: true
          },
          {
            name: "is_read",
            data: "is_read",
            title: "Status",
            targets: 2,
            sortable: true,
            filterable: true
          }
        ]
      });

      // functon click row
      $("#notification tbody").on("click", "tr", function() {
        var data = table.row(this).data();
        read_Notif(data.notification_id);
        table.ajax.reload();
      });
    });
  }

  render() {
    return (
      <div className="animated fadeIn">
        <Card>
          <CardBody>
            <table
              id="notification"
              className="table table-striped "
              style={{ width: "100%" }}
            />
          </CardBody>
        </Card>
      </div>
    );
  }
}
