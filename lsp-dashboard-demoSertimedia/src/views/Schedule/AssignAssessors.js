import React, { Component } from "react";
import { Button } from "reactstrap";
import { Input, Icon, Table } from "antd";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";
import Axios from "axios";
import {
  NotificationContainer,
  NotificationManager
} from "react-notifications";

import {
  baseUrl,
  path_assessments,
  getData,
  path_assign_assessors,
  path_users
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
  assessment_id: any
};

class AssignAssessors extends Component<Props> {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      offset: 0,
      filteredInfo: null,
      searchText: "",
      sub_schema_number: "",
      assessors_id: ""
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
    const auth = Digest(
      `${path_assessments}/${this.props.assessment_id}${path_assign_assessors}`,
      "GET"
    );
    reqwest({
      url: `${baseUrl}${path_assessments}/${this.props.assessment_id +
        path_assign_assessors}?search=${searchText}`,
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
    const pathAlternative = path_users;
    const auth = Digest(pathAlternative, "GET");
    reqwest({
      url: baseUrl + pathAlternative + "?role_code=ACS",
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
    let gender = "M,F";
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

    if (filters.gender_code !== undefined) {
      switch (filters.gender_code[0]) {
        case "M":
          gender = "M";
          break;

        case "F":
          gender = "F";
          break;

        default:
          break;
      }
    }
    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting,
      gender_code: gender
      // sortOrder: sorter.order,
      // ...filters
    });
  };

  handleAssign = row => {
    const path =
      path_assessments + "/" + this.props.assessment_id + "/assessors";
    var formData = new FormData();
    formData.append("assessor_id", row.user_id);
    Axios(getData(path, "POST", formData))
      .then(res => {
        if (res.status === 201) {
          setTimeout(() => {
            this.setState({
              loading: false
            });
          }, 1000);
          NotificationManager.success(
            `${multiLanguage.SuccessAsign} ${row.first_name}`,
            "SUCCESS",
            5000
          );
          this.get();
          window.location.reload();
        }
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
        render: (_value, row) => {
          return (
            <div>
              {row.first_name} {row.last_name}
            </div>
          );
        }
      },
      {
        key: "gender_code",
        title: (
          <h5 style={{ fontWeight: "bold", textAlign: "center" }}>
            {multiLanguage.gender}
          </h5>
        ),
        dataIndex: "gender_code",
        filters: [
          { text: multiLanguage.male, value: "M" },
          { text: multiLanguage.female, value: "F" }
        ],
        width: "10%",
        render: gender_code => {
          if (gender_code === "M") {
            return multiLanguage.male;
          } else {
            return multiLanguage.female;
          }
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
        render: (_value, row) => {
          return (
            <div>
              <Button
                className="btn btn-success"
                onClick={this.handleAssign.bind(this, row)}
              >
                <i className="fa fa-plus"></i> Assign
              </Button>
            </div>
          );
        }
      }
    ];
    return (
      <React.Fragment>
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
        <NotificationContainer />
      </React.Fragment>
    );
  }
}

export default AssignAssessors;
