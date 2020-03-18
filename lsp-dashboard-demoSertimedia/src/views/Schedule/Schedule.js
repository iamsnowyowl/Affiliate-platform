import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Link } from "react-router-dom";
import { Input, Icon, Table, Popconfirm, Modal, Select } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";

import {
  path_assessments,
  baseUrl,
  getData,
  getRole,
  formatDate
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";
// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import { AvForm, AvField } from "availity-reactstrap-validation";
import LoadingOverlay from "react-loading-overlay";
const Search = Input.Search;

class Schedule extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      modal: false,
      modalChangeState: false,
      payload: [],
      defaultStatus: "",
      payloadUserData: [],
      messageModal: "",
      assessmentID: []
    };
  }

  getColumnSearchProps = dataIndex => ({
    filterDropDown: ({
      setSelectedKeys,
      selectedKeys,
      confirm,
      clearFilters
    }) => (
      <div style={{ padding: 8 }}>
        <Input
          ref={node => {
            this.searcInput = node;
          }}
          placeholder={`Search ${dataIndex}`}
          value={selectedKeys[0]}
          onChange={e =>
            setSelectedKeys(e.target.velue ? [e.target.value] : [])
          }
          onPressEnter={() => this.handleSearch(selectedKeys, confirm)}
          style={{ width: 188, marginBottom: 8, display: "block" }}
        />
        <Button
          type="primary"
          onClick={() => this.handleSearch(selectedKeys, confirm)}
          icon="search"
          size="small"
          style={{ width: 90, marginRight: 8 }}
        >
          {multiLanguage.search}
        </Button>
        <Button
          onClick={() => this.handleReset(clearFilters)}
          size="small"
          style={{ width: 90 }}
        >
          {multiLanguage.reset}
        </Button>
      </div>
    ),
    filterIcon: filtered => (
      <Icon type="search" style={{ color: filtered ? "#1890ff" : undefined }} />
    ),
    onFilter: (value, record) =>
      record[dataIndex]
        .toString()
        .toLowerCase()
        .includes(value.toLowerCase()),
    onFilterDropdownVisibleChange: visible => {
      if (visible) {
        setTimeout(() => this.searcInput.select());
      }
    },
    render: text => (
      <Highlighter
        highlightStyle={{ backgroundColor: "#ffc069", padding: 0 }}
        searchWords={[this.state.searchText]}
        autoEscape
        textToHighlight={text.toString()}
      />
    )
  });

  handleSearch = searchText => {
    this.setState({ loading: true });
    const auth = Digest(path_assessments, "GET");
    reqwest({
      url: baseUrl + path_assessments + "?search=" + searchText,
      method: "GET",
      data: {
        limit: 10
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    }).then(response => {
      const pagination = { ...this.state.pagination };
      const count = parseInt(response.count, 10);
      pagination.total = count;
      this.setState({
        loading: false,
        data: response.data,
        pagination
      });
    });
  };

  handleChange = event => {
    if (event.target.value === "") {
      this.get();
    }
  };

  handleReset = clearFilters => {
    clearFilters();
    this.setState({ searchText: "" });
  };

  get = (
    params = {
      sort: "-created_date",
      last_activity_state:
        "ADMIN_CONFIRM_FORM,PORTFOLIO_APPLICANT_COMPLETED,ASSESSOR_READY,ADMIN_READY,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_MEMBER_READY,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY,REQUEST_BLANKO_SENDING,PRINT_CERTIFICATE"
    }
  ) => {
    this.setState({ loading: true });
    const auth = Digest(path_assessments, "GET");
    reqwest({
      url: baseUrl + path_assessments,
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      contentType: "application/json",
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date
      },
      type: "json"
    })
      .then(response => {
        const pagination = { ...this.state.pagination };
        const count = parseInt(response.count, 10);
        pagination.total = count;
        this.setState({
          loading: false,
          data: response.data,
          pagination
        });
      })
      .catch(error => {
        if (error.status === 401) {
          localStorage.clear();
          window.location.replace("/login");
        } else if (error.status === 419) {
          this.errorTrial();
        }
      });
  };

  errorTrial = () => {
    Modal.error({
      title: "Pesan Error",
      content:
        "Masa trial anda telah habis,Harap menghubungi Admin NAS untuk info lebih lanjut",
      onOk() {
        localStorage.clear();
        window.location.replace("/login");
      }
    });
  };

  componentDidMount() {
    this.get();
    var json = JSON.parse(localStorage.getItem("userdata"));
    this.setState({
      payloadUserData: json
    });
  }

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    switch (sorter.order) {
      case "ascend":
        sorting = sorter.field;
        break;

      case "descend":
        sorting = "-" + sorter.field;
        break;

      default:
        break;
    }

    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting,
      last_activity_state:
        "ADMIN_CONFIRM_FORM,PORTFOLIO_APPLICANT_COMPLETED,ASSESSOR_READY,ADMIN_READY,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_MEMBER_READY,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY,REQUEST_BLANKO_SENDING,PRINT_CERTIFICATE"
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  handleReject = value => {
    const path =
      path_assessments + "/" + value + "/change_state/ASSESSMENT_REJECTED";
    Axios(getData(path, "PUT")).then(response => {
      if (response.data.responseStatus === "SUCCESS") {
        this.setState({ loading: true });
        setTimeout(() => {
          this.setState({
            loading: false
          });
        }, 1000);
        this.get();
      } else {
        alert("Cannot confirm");
      }
    });
  };

  handleConfirm = value => {
    const path =
      path_assessments + "/" + value + "/change_state/ADMIN_CONFIRM_FORM";
    Axios(getData(path, "PUT")).then(response => {
      if (response.data.responseStatus === "SUCCESS") {
        this.setState({ loading: true });
        setTimeout(() => {
          this.setState({
            loading: false
          });
        }, 1000);
        this.get();
      } else {
        alert("Cannot confirm");
      }
    });
  };

  handleChangeState = event => {
    this.setState({
      status: event.target.value
    });
  };

  viewModal = value => {
    this.setState({
      assessmentID: value,
      modalChangeState: !this.state.modalChangeState
    });
  };

  error = () => {
    Modal.error({
      title: "Error",
      content: this.state.messageModal
    });
  };

  handleSubmit = event => {
    event.preventDefault();
    this.setState({ loading: true, modalChangeState: false });
    this.viewModal();
    const { assessment_id } = this.state.assessmentID;
    const path =
      path_assessments +
      "/" +
      assessment_id +
      "/change_state/" +
      this.state.status;
    Axios(getData(path, "PUT"))
      .then(() => {
        this.setState({ loading: false });
        window.location.reload();
      })
      .catch(error => {
        const messageError = error.response.data.error.message;
        console.log(messageError);
        this.setState({
          loading: false,
          messageModal:
            "Mohon Assign Admin/Anggota Pleno sebelum mengubah ke Pleno"
        });
        this.error();
      });
  };

  cancelModalChangeState = () => {
    this.setState({
      modalChangeState: false
    });
  };

  deleted = value => {
    this.setState({
      loading: true
    });
    const auth = Digest(path_assessments + "/" + value, "DELETE");
    const options = {
      method: auth.method,
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "multipart/form-data"
      },
      url: baseUrl + path_assessments + "/" + value,
      data: null
    };
    Axios(options).then(() => {
      this.setState({
        loading: false
      });
      this.get();
      // window.location.reload();
    });
  };

  render() {
    const columns = [
      {
        key: "title",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.assessmentName}</h5>
        ),
        dataIndex: "title",
        sorter: true,
        width: "20%",
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "address",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.address}</h5>,
        dataIndex: "address",
        width: "15%",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "start_date",
        title: (
          <h5 style={{ fontWeight: "bold" }}>{multiLanguage.assessmentDate}</h5>
        ),
        width: "15%",
        dataIndex: "start_date",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "tuk_name",
        width: "15%",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.tukName}</h5>,
        dataIndex: "tuk_name",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "schema_label",
        align: "center",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.schema}</h5>,
        dataIndex: "schema_label",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "last_activity_state",
        align: "center",
        title: <h5 style={{ fontWeight: "bold" }}>Status</h5>,
        dataIndex: "last_activity_state",
        sorter: true,
        render: value => {
          if (value === "TUK_COMPLETE_FORM") {
            return (
              <div style={{ textAlign: "left" }}>
                {multiLanguage.stateRequestAssessment}
              </div>
            );
          } else if (value === "ADMIN_CONFIRM_FORM") {
            return (
              <div style={{ textAlign: "left" }}>
                {multiLanguage.stateReadyPraAssessment}
              </div>
            );
          } else if (value === "ON_REVIEW_APPLICANT_DOCUMENT") {
            return (
              <div style={{ textAlign: "left" }}>{multiLanguage.reviewDoc}</div>
            );
          } else if (value === "ON_COMPLETED_REPORT") {
            return (
              <div style={{ textAlign: "left" }}>
                {multiLanguage.statePraAsesment}
              </div>
            );
          } else if (value === "REAL_ASSESSMENT") {
            return (
              <div style={{ textAlign: "left" }}>{multiLanguage.stateReal}</div>
            );
          } else if (value === "PLENO_DOCUMENT_COMPLETED") {
            return <div style={{ textAlign: "left" }}>Pleno</div>;
          } else if (value === "PLENO_REPORT_READY") {
            return (
              <div style={{ textAlign: "left" }}>
                {multiLanguage.statePlenoFinish}
              </div>
            );
          } else if (value === "PRINT_CERTIFICATE") {
            return (
              <div style={{ textAlign: "left" }}>
                {multiLanguage.certificate}
              </div>
            );
          } else {
            return value;
          }
        }
      },
      {
        key: "assessment_id",
        align: "center",
        title: <h5 style={{ fontWeight: "bold" }}>{multiLanguage.action}</h5>,
        width: "2%",
        dataIndex: "assessment_id",
        render: (value, row) => {
          var { user_id } = this.state.payloadUserData;
          var Role_ACS = (
            <div>
              <a
                href={
                  path_assessments + "/" + value + "/assign/" + user_id
                }
                className="btn btn-success"
                title={multiLanguage.asesi}
              >
                <i className="fa fa-users" />
              </a>
            </div>
          );

          var Role_APL = (
            <div>
              <a
                href={path_assessments + "/" + value + "/portfolio"}
                className="btn btn-success"
                title="PortFolio"
              >
                {multiLanguage.portfolio}
              </a>
            </div>
          );

          var assign = (
            <Link to={`${path_assessments}/${value}/assign`}>
              <Button
                className="btn-success"
                title="Assign"
                style={{ width: "38px" }}
              >
                <i className="fa fa-users" />
              </Button>
            </Link>
          );

          var letters = (
            <Link to={`${path_assessments}/${value}/generate`}>
              <Button className="btn-warning" title={multiLanguage.document}>
                <i className="fa fa-file-pdf-o" />
              </Button>
            </Link>
          );

          var changeState = (
            <Button
              className="btn btn-primary-sm"
              title={multiLanguage.Assessment}
              onClick={() => {
                this.viewModal(row);
              }}
              title={multiLanguage.changeState}
            >
              <i className="fa fa-info-circle" />
            </Button>
          );

          var deleteAssessment = (
            <Popconfirm
              title={multiLanguage.confirmDelete}
              onConfirm={this.deleted.bind(this, value)}
              onCancel={this.cancel}
              okText={multiLanguage.yes}
              cancelText={multiLanguage.no}
            >
              <button
                className="btn btn-danger delete-button col-md-auto"
                title={multiLanguage.delete}
                style={{ width: "38px" }}
              >
                <i className="fa fa-trash"> </i>
              </button>
            </Popconfirm>
          );

          if (getRole() === "ACS") {
            return Role_ACS;
          } else if (getRole() === "APL") {
            return Role_APL;
          } else {
            if (
              row.last_activity_state === "ADMIN_CONFIRM_FORM" ||
              row.last_activity_state === "ON_REVIEW_APPLICANT_DOCUMENT" ||
              row.last_activity_state === "ON_COMPLETED_REPORT"
            ) {
              return (
                <div style={{ textAlign: "left" }}>
                  {assign} {letters} {changeState} {deleteAssessment}
                </div>
              );
            } else {
              return (
                <div style={{ textAlign: "left" }}>
                  {assign} {letters} {changeState}
                </div>
              );
            }
          }
        }
      }
    ];
    const { defaultStatus } = this.state;
    return (
      <div className="animated fadeIn">
        <LoadingOverlay active={this.state.loading} spinner text="Loading...">
          <Modal
            title={`${multiLanguage.change} State ${multiLanguage.Assessment}`}
            visible={this.state.modalChangeState}
            onOk={this.handleSubmit}
            onCancel={this.cancelModalChangeState}
          >
            <AvForm>
              <AvField
                type="select"
                name="change_state"
                label={`${multiLanguage.change} Status`}
                onChange={this.handleChangeState}
              >
                <option value="">{multiLanguage.select}</option>
                <option value="ADMIN_CONFIRM_FORM">
                  {multiLanguage.stateReadyPraAssessment}
                </option>
                <option value="ON_REVIEW_APPLICANT_DOCUMENT">
                  {multiLanguage.reviewDoc}
                </option>
                <option value="ON_COMPLETED_REPORT">
                  {multiLanguage.PraAssessmentCompleted}
                </option>
                <option value="REAL_ASSESSMENT">
                  {multiLanguage.stateReal}
                </option>
                <option value="PLENO_DOCUMENT_COMPLETED">Pleno</option>
                <option value="PLENO_REPORT_READY">
                  {multiLanguage.PlenoFinish}
                </option>
                <option value="PRINT_CERTIFICATE">
                  {multiLanguage.certificate}
                </option>
                <option value="COMPLETED">{multiLanguage.completed}</option>
              </AvField>
            </AvForm>
          </Modal>
          <Card>
            <CardHeader>
              <Row>
                <Col md="6">
                  <h5
                    style={{
                      textDecoration: "underline",
                      color: "navy"
                    }}
                  >
                    {multiLanguage.list} {multiLanguage.Assessment}
                  </h5>
                </Col>
                {localStorage.getItem("role") === "DEV" ||
                localStorage.getItem("role") === "ADM" ||
                localStorage.getItem("role") === "SUP" ? (
                  <Col md="6" className="mb-3 mb-xl-0">
                    <Link to={path_assessments + "/input-data"}>
                      <Button
                        className="float-md-right"
                        size="default"
                        color="primary"
                      >
                        <i className="fa fa-plus" />{" "}
                        {multiLanguage.add + " " + multiLanguage.Assessment}
                      </Button>
                    </Link>
                  </Col>
                ) : (
                  ""
                )}
              </Row>
            </CardHeader>
            <CardBody>
              {`${multiLanguage.searching} `}
              <Search
                placeholder={multiLanguage.search}
                onSearch={this.handleSearch}
                onChange={this.handleChange}
                style={{ width: 310 }}
              />{" "}
              <p />
              <Table
                rowKey={record => record.assessment_id}
                columns={columns}
                dataSource={this.state.data}
                pagination={this.state.pagination}
                loading={this.state.loading}
                onChange={this.handleTableChange}
                stripe
              />
            </CardBody>
          </Card>
        </LoadingOverlay>
      </div>
    );
  }
}

export default Schedule;
