import React, { Component } from "react";
import { Button, Card, CardHeader, CardBody } from "reactstrap";
import { Input, Icon, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import { NotificationManager } from "react-notifications";
import {
  path_assessments,
  baseUrl,
  getData,
  path_applicant
} from "../../components/config/config";
import { Digest } from "../../containers/Helpers/digest";
import { multiLanguage } from "../../components/Language/getBahasa";

import "antd/dist/antd.css";
import "../../css/TableAntd.css";
import "../../css/loaderDataTable.css";

const Search = Input.Search;

export default class AsesiReadyAssign extends Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      name: "",
      hidden: true,
      message: ""
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
    const { assessment_id } = this.props;
    var url =
      baseUrl +
      path_assessments +
      "/" +
      assessment_id +
      path_applicant +
      "?search=" +
      searchText;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + path_applicant,
      "GET"
    );
    reqwest({
      url: url,
      method: "GET",
      data: {
        limit: 10,
        assessor_id: "0"
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
    const { assessment_id } = this.props;
    this.setState({ loading: true });
    const auth = Digest(
      path_assessments + "/" + assessment_id + path_applicant,
      "GET"
    );
    reqwest({
      url: baseUrl + path_assessments + "/" + assessment_id + path_applicant,
      method: "GET",
      data: {
        limit: 10,
        assessor_id: "0",
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
    this.get();
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

  handleClick(assessment_id) {
    window.location.assign(`${path_assessments}/${assessment_id}/assign`);
  }

  handleAssign = row => {
    const { assessment_id, title } = this.props.payloadDetailAssessment;
    const { assessment_applicant_id } = row;
    const { assessor_id } = this.props;
    this.setState({
      loading: true
    });
    const path =
      path_assessments +
      "/" +
      assessment_id +
      path_applicant +
      "/" +
      assessment_applicant_id;
    var data = {};
    data["assessor_id"] = assessor_id;
    Axios(getData(path, "PUT", data))
      .then(res => {
        setTimeout(() => {
          this.setState({
            loading: false
          });
        }, 1000);
        NotificationManager.success(
          `${multiLanguage.SuccessAsign} ${row.first_name} ${row.last_name} to ${title} `,
          "SUCCESS",
          5000
        );
        this.get();
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
        key: "first_name",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.name}
          </h5>
        ),
        dataIndex: "first_name",
        render: (_value, row) => {
          const last_name = row.last_name !== "Undefined" ? row.last_name : "";
          const full = row.first_name + " " + last_name;
          return <div>{row.applicant_id !== "0" ? full : row.full_name}</div>;
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
        render: value => {
          return <div>{value}</div>;
        }
      },
      {
        key: "user_id",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.action}
          </h5>
        ),
        dataIndex: "user_id",
        width: "17%",
        render: (_value, row) => {
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
      <div className="animated fadeIn">
        <Card>
          <CardHeader>{multiLanguage.list} Peserta</CardHeader>
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
              rowKey={record => record.assessment_applicant_id}
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
