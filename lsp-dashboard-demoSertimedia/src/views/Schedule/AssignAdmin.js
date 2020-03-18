import React from "react";
import { Link, Redirect } from "react-router-dom";
import { Button, Card, CardHeader, CardBody, CardFooter } from "reactstrap";
import { Input, Icon, Table } from "antd";
import LoadingOverlay from "react-loading-overlay";
import Highlighter from "react-highlight-words";
import reqwest from "reqwest";

import {
  path_users,
  baseUrl,
  path_assessments,
  getData
} from "../../components/config/config";
import Axios from "axios";
import { multiLanguage } from "../../components/Language/getBahasa";
import {
  NotificationManager,
  NotificationContainer
} from "react-notifications";
import { Digest } from "../../containers/Helpers/digest";

const Search = Input.Search;

export default class AssignAdmin extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      data: [],
      pagination: {},
      loading: false,
      assignAdmin: false,
      offset: 0,
      filteredInfo: null,
      searchText: ""
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
    var url = baseUrl + path_users + "?search=" + searchText + "&role_code=ADM";
    this.setState({ loading: true });
    const auth = Digest(path_users, "GET");
    reqwest({
      url: url,
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

  get = (params = { role_code: "ADM" }) => {
    this.setState({ loading: true });
    const auth = Digest(path_users, "GET");
    reqwest({
      url: baseUrl + path_users,
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

  handleTableChange = (pagination, filters, sorter) => {
    const pager = { ...this.state.pagination };
    pager.current = pagination.current;
    this.setState({
      pagination: pager
    });
    const offset = (pagination.current - 1) * pagination.pageSize;

    let sorting = "";
    let gender_code = "M,F";
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
          gender_code = "M";
          break;

        case "F":
          gender_code = "F";
          break;

        default:
          break;
      }
    }
    this.get({
      limit: pagination.pageSize,
      offset: offset,
      sort: sorting,
      gender_code: gender_code
    });
  };

  componentDidMount() {
    this.get();
  }

  assign_Admin = user_id => {
    this.setState({
      loading: true
    });
    const { assessment_id } = this.props.match.params;
    const data = {};

    data["admin_id"] = user_id;

    Axios(
      getData(path_assessments + "/" + assessment_id + "/admins", "POST", data)
    )
      .then(() => {
        this.setState({
          assignAdmin: true
        });
      })
      .catch(error => {
        this.setState({
          loading: false
        });
        if ((error.response.status = "409")) {
          NotificationManager.error(multiLanguage.alreadyAssign, "Error");
        }
        console.log("error");
      });
  };

  render() {
    const { run, assessment_id } = this.props.match.params;
    if (this.state.assignAdmin) {
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
        dataIndex: "picture",
        title: multiLanguage.picture,
        sorter: false,
        render: (value, row) => {
          return (
            <img
              src={
                baseUrl +
                path_users +
                "/" +
                row.user_id +
                "/picture?width=56&height=56"
              }
              alt="admin"
            />
          );
        }
      },
      {
        key: "first_name",
        dataIndex: "first_name",
        title: multiLanguage.name,
        sorter: true,
        render: (value, row) => {
          return value + " " + row.last_name;
        }
      },
      {
        key: "gender_code",
        dataIndex: "gender_code",
        title: multiLanguage.gender,
        filters: [
          {
            text: multiLanguage.male,
            value: "M"
          },
          {
            text: multiLanguage.female,
            value: "F"
          }
        ],
        filterMultiple: false,
        sorter: false,
        render: value => {
          if (value === "M") {
            return multiLanguage.male;
          } else if (value === "F") {
            return multiLanguage.female;
          } else {
            return value;
          }
        }
      },
      {
        key: "address",
        dataIndex: "address",
        title: multiLanguage.address,
        sorter: true,
        render: value => {
          if (value === "undefined") {
            return "-";
          } else {
            return value;
          }
        }
      },
      {
        key: "contact",
        dataIndex: "contact",
        title: multiLanguage.contact,
        sorter: true
      },
      {
        key: "actions",
        dataIndex: "assessment_id",
        title: multiLanguage.action,
        sorter: true,
        render: (value, row) => {
          return (
            <div>
              <Button
                className="btn btn-success"
                onClick={() => {
                  this.assign_Admin(row.user_id);
                }}
              >
                Assign
              </Button>
            </div>
          );
        }
      }
    ];
    return (
      <LoadingOverlay active={this.state.loading} spinner text="Loading...">
        <Card className="animated fadeIn">
          <CardHeader style={{ textAlign: "center" }}>Assign Admin</CardHeader>
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
          <CardFooter>
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
          </CardFooter>
          <NotificationContainer />
        </Card>
      </LoadingOverlay>
    );
  }
}
