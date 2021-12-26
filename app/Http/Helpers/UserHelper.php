<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserHelper
{
	public static function getStoreRequestData(Request $req)
	{
		return array_filter([
			'name' => $req->userName,
			'lastName' => $req->userLastName,
			'email' => $req->userEmail,
			'password' => $req->userPassword,
			'phoneNumber' => $req->userPhoneNumber,
			'neighborhoodId' => $req->userNeighborhoodId,
		]);
	}

	public static function getUpdateRequestData(Request $req)
	{
		return array_filter([
			'name' => $req->userName,
			'lastName' => $req->userLastName,
			'email' => $req->userEmail,
			'password' => $req->userPassword,
			'phoneNumber' => $req->userPhoneNumber,
			'neighborhoodId' => $req->userNeighborhoodId,
		]);
	}

	public static function getMailConfirmationRequestData(Request $req)
	{
		return array_filter([
			'token' => $req->token,
		]);
	}

	public static function getLoginRequestData(Request $req)
	{
		return array_filter([
			'email' => $req->email,
			'password' => $req->password,
		]);
	}

	public static function handleStoreRequest(array $data)
	{
		GenericHelper::validate(User::getStoreRequestValidator($data));

		$data['phoneNumber'] = preg_replace(User::userPhoneNumberParsePattern, "$1$2$3$4$5", $data['phoneNumber']);

		$data['passwordHash'] = Hash::make($data['password']);
		unset($data['password']);

		$data['uuid'] = GenericHelper::generateUUIDString();

		$user = self::saveUser($data);

		return $user;
	}

	public static function handleUpdateRequest(array $findData, array $updateData)
	{
		GenericHelper::validate(User::getUpdateRequestValidator($updateData));

		$user = self::updateUser($findData, $updateData);

		return $user;
	}

	public static function handleDeleteRequest(array $findData)
	{
		self::deleteUser($findData);
	}

	public static function handleMailConfirmation(array $data)
	{
		GenericHelper::validate(User::getMailConfirmationValidator($data));

		$authHandler = GenericHelper::getDefaultAuthHandler();

		$authHandler->eraseCache();

		$authHandler->checkPermission(
			$data,
			'user:confirm-mail'
		);

		$user = $authHandler->getModelFromToken(
			$data,
			User::class
		);

		if ($user->emailConfirmation != null) {
			$authHandler->removeAccess($data);
			throw new GenericAppException([__('messages.system_error')], 400);
		}

		$user->emailConfirmation = Carbon::now();

		$user->save();

		$authHandler->removeAccess($data);
	}

	public static function handleLoginRequest(array $data)
	{
		GenericHelper::validate(User::getLoginRequestValidator($data));

		return self::login($data['email'], $data['password']);
	}

	public static function handleLogoutRequest($uuid)
	{
		return self::logout($uuid);
	}

	public static function getTreatedQuery(array $data)
	{
		GenericHelper::validate(User::getQueryValidator($data));

		$fixedValues = GenericHelper::getFixedValues($data);
		$searchValues = GenericHelper::getSearchValues($data);
		$compareValues = GenericHelper::getCompareValues($data);

		$builder = User::where($fixedValues);
		$builder = GenericHelper::handleSearchValues($builder, $searchValues);
		$builder = GenericHelper::handleCompareValues($builder, $compareValues);

		return $builder;
	}

	public static function getUser(array $data, bool $enableNotFoundError = true)
	{
		$builder = self::getTreatedQuery($data);

		$user = $builder->first();

		if ($enableNotFoundError && !isset($user)) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $user;
	}

	public static function getUsers(array $data, bool $enableNotFoundError = false)
	{
		$builder = self::getTreatedQuery($data);

		$users = $builder->get();

		if ($enableNotFoundError && sizeof($users) === 0) {
			throw new GenericAppException([__('messages.not_found_error')], 404);
		}

		return $users;
	}

	public static function deleteUser(array $data)
	{
		$user = self::getUser($data);

		if (!$user->delete()) {
			throw new GenericAppException([__('messages.delete_error')], 500);
		};
	}

	protected static function saveUser(array $data)
	{
		GenericHelper::validate(User::getStoreValidator($data));

		$user = new User($data);
		$user->save();

		return $user;
	}

	protected static function updateUser(array $findData, array $updateData)
	{
		$user = self::getUser($findData);

		GenericHelper::validate(User::getUpdateValidator($updateData));

		$user->fill($updateData);

		if (sizeof($user->getDirty()) === 0) {
			throw new GenericAppException([__('messages.up_to_date_error')], 400);
		}

		$user->save();

		return $user;
	}

	protected static function login(string $email, string $password)
	{
		$user = self::getUser([
			'email' => $email,
			'emailConfirmation_different_than' => null,
		]);

		if (!Hash::check($password, $user->passwordHash)) {
			throw new GenericAppException([__('validation.password')], 401);
		}

		return $user->createToken(
			'general-user-login',
			['user:general'],
		)->plainTextToken;
	}

	protected static function logout($uuid)
	{
		$authHandler = GenericHelper::getDefaultAuthHandler();

		$authHandler->removeAllAccesses(['uuid' => $uuid]);
	}
}
