import { Link, useForm, usePage } from "@inertiajs/react";
import React, { FormEventHandler } from "react";
import MiniCartDropDown from "@/Components/app/MiniCartDropDown";
import { PageProps } from "@/types";
import { MagnifyingGlassIcon } from "@heroicons/react/24/outline";

function Navbar() {
  const { auth, departments, keyword } = usePage().props;
  const { user } = auth;

  const searchForm = useForm<{
    keyword: string;
  }>({
    keyword: keyword || "",
  });

  const { url } = usePage();

  const onSubmit: FormEventHandler = (e) => {
    e.preventDefault();

    searchForm.get(url, {
      preserveScroll: true,
      preserveState: true,
    });
  };

  return (
    <>
      <div className="navbar bg-base-100">
        <div className="flex-1">
          <Link href={route("dashboard")} className="btn btn-ghost text-xl">
            UniStore
          </Link>
        </div>
        <div className="flex-none gap-4">
          <form onSubmit={onSubmit} className="join flex-1">
            <div className="flex-1">
              <input
                type="text"
                value={searchForm.data.keyword}
                onChange={(e) => searchForm.setData("keyword", e.target.value)}
                className="input input-bordered join-item w-full"
                placeholder="Search ....."
              />
            </div>
            <div className="indicator">
              <button className="btn join-item">
                <MagnifyingGlassIcon className={"size-4"} />
                Search
              </button>
            </div>
          </form>

          <MiniCartDropDown />
          {user && (
            <div className="dropdown dropdown-end">
              <div
                tabIndex={0}
                role="button"
                className="btn btn-ghost btn-circle avatar"
              >
                <div className="w-10 rounded-full">
                  <img
                    alt="Tailwind CSS Navbar component"
                    src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                  />
                </div>
              </div>
              <ul
                tabIndex={0}
                className="menu menu-sm dropdown-content bg-base-100 border border-slate-600  rounded-box z-[100] mt-3 w-52 p-2 shadow-lg shadow-gray-700"
              >
                <li>
                  <Link
                    href={route("profile.edit")}
                    className="justify-between"
                  >
                    Profile
                    <span className="badge">New</span>
                  </Link>
                </li>
                <li>
                  <Link href={route("logout")} method={"post"} as={"button"}>
                    Logout
                  </Link>
                </li>
              </ul>
            </div>
          )}
          {!user && (
            <div className="flex gap-3">
              <Link href={route("login")} className="btn">
                Login
              </Link>
              <Link href={route("register")} className="btn btn-primary">
                Sign up
              </Link>
            </div>
          )}
        </div>
      </div>

      <div className="navbar bg-base-100 border-t min-h-4">
        <div className="navbar-center overflow-x-auto hidden lg:flex">
          <ul className="menu menu-horizontal px-1 z-20 py-0">
            {departments.map((department) => (
              <li key={department.id}>
                {
                  <Link href={route("product.byDepartment", department.slug)}>
                    {department.name}
                  </Link>
                }
              </li>
            ))}
          </ul>
        </div>
      </div>
    </>
  );
}

export default Navbar;
