import React, { Component } from "react";
import { Link, Redirect } from "react-router-dom";
import { Card, CardBody, Button } from "reactstrap";
import { Input, Icon, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";
import LoadingOverlay from "react-loading-overlay";

import {
  baseUrl,
  path_applicant,
  path_assessments,
  getData,
  path_assign_asesi
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "react-notifications/lib/notifications.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";
import "../../css/Notif.css";

const Search = Input.Search;

type Props = {
  assessment_id: any,
  sub_schema_number: any
};

class AssignApplicant extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      assignAsesi: false,
      searchText: "",
      assessor_id: "",
      payloadDetailAssessment: []
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
    const { assessment_id, sub_schema_number } = this.props.match.params;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments +
        "/" +
        assessment_id +
        path_assign_asesi +
        "/" +
        sub_schema_number,
      "GET"
    );
    reqwest({
      url:
        baseUrl +
        path_assessments +
        "/" +
        assessment_id +
        path_assign_asesi +
        "/" +
        sub_schema_number +
        "?search=" +
        searchText,
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
    const { assessment_id, sub_schema_number } = this.props.match.params;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments +
        "/" +
        assessment_id +
        path_assign_asesi +
        "/" +
        sub_schema_number,
      "GET"
    );
    reqwest({
      url:
        baseUrl +
        path_assessments +
        "/" +
        assessment_id +
        path_assign_asesi +
        "/" +
        sub_schema_number,
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
        }
      });
  };

  componentDidMount() {
    const { assessment_id } = this.props.match.params;
    Axios(getData(path_assessments + "/" + assessment_id, "GET")).then(
      response => {
        this.setState({
          payloadDetailAssessment: response.data.data
        });
      }
    );
    this.get();
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
      sort: sorting
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  handleAssign = row => {
    const { assessment_id, sub_schema_number } = this.props.match.params;
    const { tuk_id } = this.state.payloadDetailAssessment;
    this.setState({
      loading: true
    });
    const path = path_assessments + "/" + assessment_id + path_applicant;
    var formData = new FormData();
    formData.append("applicant_id", row.user_id);
    formData.append("sub_schema_number", sub_schema_number);
    formData.append("tuk_id", tuk_id);
    formData.append("assessment_id", assessment_id);
    Axios(getData(path, "POST", formData))
      .then(() => {
        this.setState({
          assignAsesi: true
        });
        // setTimeout(() => {
        //   this.setState({
        //     loading: false
        //   });
        // }, 1000);
        // NotificationManager.success(
        //   `${multiLanguage.SuccessAsign} ${row.first_name} ${row.last_name} ke ${multiLanguage.Assessment} ${schema_label}`,
        //   multiLanguage.success,
        //   8000
        // );
        // this.get();
      })
      .catch(err => {
        console.log("error");
        if (err) {
          this.setState({
            loading: false
          });
          NotificationManager.error(multiLanguage.alreadyAssign, "Error", 5000);
        }
      });
  };

  render() {
    const { run, assessment_id } = this.props.match.params;
    if (this.state.assignAsesi) {
      return (
        <Redirect
          to={{
            pathname: path_assessments + "/" + assessment_id + "/assign",
            state: {
              runs: run
            }
          }}
        />
      );
    }
    const columns = [
      {
        key: "picture",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.picture}
          </h5>
        ),
        dataIndex: "picture",
        width: "3%",
        render: value => {
          return (
            <img alt="asesi" src={baseUrl + value + "?width=56&height=56"} />
          );
        }
      },
      {
        key: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.name}
          </h5>
        ),
        dataIndex: "first_name",
        sorter: true,
        width: "10%",
        render: (value, row) => {
          return (
            <div>
              {row.first_name} {row.last_name}
            </div>
          );
        }
      },
      {
        key: "contact",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.contact}
          </h5>
        ),
        dataIndex: "contact",
        width: "10%"
      },
      {
        key: "user_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "user_id",
        width: "5%",
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
      <LoadingOverlay active={this.state.loading} spinner text="Loading...">
        <React.Fragment>
          <Card>
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
                rowKey={record => record.user_id}
                columns={columns}
                dataSource={this.state.data}
                pagination={this.state.pagination}
                loading={this.state.loading}
                onChange={this.handleTableChange}
                striped={true}
              />
              <Link
                to={{
                  pathname: path_assessments + "/" + assessment_id + "/assign",
                  state: {
                    runs: run
                  }
                }}
              >
                <Button type="submit" size="md" color="danger">
                  <i className="fa fa-chevron-left" /> {multiLanguage.back}
                </Button>
              </Link>
            </CardBody>
          </Card>

          <NotificationContainer />
        </React.Fragment>
      </LoadingOverlay>
    );
  }
}

export default AssignApplicant;
