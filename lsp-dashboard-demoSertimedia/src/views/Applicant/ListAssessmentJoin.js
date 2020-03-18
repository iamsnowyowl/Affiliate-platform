import React, { Component } from "react";
import { Table } from "antd";
import { Button } from "reactstrap";
import { multiLanguage } from "../../components/Language/getBahasa";
import {
  path_assessments,
  path_applicant,
  getData
} from "../../components/config/config";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";
import Axios from "axios";

type Props = {
  payloadListAssessment: any,
  applicant_id: any,
  join_request_id: any
};

class ListAssessmentJoin extends Component<Props> {
  state = {
    selectedRowKeys: [], // Check here to configure the default column
    loading: false
  };

  start = () => {
    this.setState({ loading: true });
    // ajax request after empty completing
    setTimeout(() => {
      this.setState({
        selectedRowKeys: [],
        loading: false
      });
    }, 1000);
  };

  handleAssign = row => {
    const { applicant_id, join_request_id } = this.props;
    const { sub_schema_number, tuk_id, assessment_id, title } = row;

    this.setState({
      loading: true
    });
    const path = path_assessments + "/" + assessment_id + path_applicant;
    var formData = new FormData();
    formData.append("applicant_id", applicant_id);
    formData.append("sub_schema_number", sub_schema_number);
    formData.append("tuk_id", tuk_id);
    formData.append("assessment_id", assessment_id);
    formData.append("join_request_id", join_request_id);

    Axios(getData(path, "POST", formData))
      .then(res => {
        setTimeout(() => {
          this.setState({
            loading: false
          });
        }, 1000);
        NotificationManager.success(
          `berhasil mendaftarkan asesi ke assessment ${title} `,
          multiLanguage.success,
          5000
        );
      })
      .catch(err => {
        if (err) {
          this.setState({
            loading: false
          });
          NotificationManager.error(multiLanguage.alreadyAssign, "Error", 5000);
        }
      });
  };

  render() {
    const columns = [
      {
        key: "title",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentName}
          </h5>
        ),
        dataIndex: "title",
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "address",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.address}
          </h5>
        ),
        dataIndex: "address",
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "start_date",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentDate}
          </h5>
        ),
        dataIndex: "start_date",
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "tuk_name",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.tukName}
          </h5>
        ),
        dataIndex: "tuk_name",
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "created_date",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.schema}
          </h5>
        ),
        dataIndex: "schema_label",
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "assessment_id",
        align: "center",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "assessment_id",
        render: (value, row) => {
          return (
            <div>
              <Button
                className="btn btn-success"
                onClick={this.handleAssign.bind(this, row)}
              >
                <i className="fa fa-plus" /> Assign
              </Button>
            </div>
          );
        }
      }
    ];

    return (
      <div>
        <Table
          columns={columns}
          dataSource={this.props.payloadListAssessment}
        />
        <NotificationContainer />
      </div>
    );
  }
}

export default ListAssessmentJoin;
