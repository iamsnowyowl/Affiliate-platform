import React, { Component } from "react";
import { Row, Col, Card, CardHeader, CardBody, Button } from "reactstrap";
import { Input, Icon, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";

import {
  path_assessments,
  baseUrl,
  getData
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";
// import {SearchData} from '../../components/SearchTable/SearchData';

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
// import style from '../../css/style.css';

const Search = Input.Search;
class ScheduleAsesi extends Component {
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
      payload: [],
      payloadUserData: []
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

  get = (params = {}) => {
    this.setState({ loading: true });
    const auth = Digest("/me" + path_assessments, "GET");
    reqwest({
      url: baseUrl + "/me" + path_assessments,
      method: "GET",
      data: {
        limit: 10,
        ...params
      },
      // contentType: 'application/json',
      headers: {
        Authorization: auth.digest,
        "X-Lsp-Date": auth.date,
        "Content-Type": "application/json"
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

  handleTableChange = (pagination, _filters, sorter) => {
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
      sort: sorting
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

  handleDelete = value => {
    const path = path_assessments + "/" + value;
    const data = null;
    Axios(getData(path, "DELETE", data)).then(response => {
      if (response.data.responseStatus === "SUCCESS") {
        this.setState({ loading: true });
        setTimeout(() => {
          this.setState({
            loading: false
          });
        }, 1000);
        this.get();
      } else {
        alert("error");
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

  render() {
    const columns = [
      {
        key: "title",
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
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.address}
          </h5>
        ),
        dataIndex: "address",
        sorter: true,
        render: value => {
          return <div style={{ textAlign: "left" }}>{value}</div>;
        }
      },
      {
        key: "start_date",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.assessmentDate} TUK
          </h5>
        ),
        dataIndex: "start_date",
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "tuk_name",
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
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.tglSubmit}
          </h5>
        ),
        dataIndex: "created_date",
        render: value => {
          return <div style={{ textAlign: "center" }}>{value}</div>;
        }
      },
      {
        key: "last_activity_state",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>Status</h5>
        ),
        dataIndex: "last_activity_state",
        render: value => {
          if (value === "TUK_COMPLETE_FORM") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.stateRequestAssessment}
              </div>
            );
          } else if (value === "ADMIN_CONFIRM_FORM") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.stateReadyPraAssessment}
              </div>
            );
          } else if (value === "ON_REVIEW_APPLICANT_DOCUMENT") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.stateReview}
              </div>
            );
          } else if (value === "ON_COMPLETED_REPORT") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.statePraAsesment}
              </div>
            );
          } else if (value === "REAL_ASSESSMENT") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.stateReal}
              </div>
            );
          } else if (value === "PLENO_REPORT_READY") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.statePlenoFinish}
              </div>
            );
          } else if (value === "PLENO_DOCUMENT_COMPLETED") {
            return <div style={{ textAlign: "center" }}>Pleno</div>;
          } else if (value === "ASSESSMENT_REJECTED") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.stateAsesmentReject}
              </div>
            );
          } else if (value === "PRINT_CERTIFICATE") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.stateCertificate}
              </div>
            );
          } else if (value === "TUK_SEND_REQUEST_ASSESSMENT") {
            return (
              <div style={{ textAlign: "center" }}>
                {multiLanguage.tukRequest}
              </div>
            );
          } else {
            return value;
          }
        }
      },
      {
        key: "assessment_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "assessment_id",
        render: (value, row) => {
          console.log(row);
          return (
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
        }
      }
    ];
    return (
      <div className="animated fadeIn">
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
                  Data {multiLanguage.Assessment}
                </h5>
              </Col>
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
      </div>
    );
  }
}

export default ScheduleAsesi;
